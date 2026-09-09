<?php

namespace App\Services;

use App\Models\MikrotikDevice;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class MikrotikService
{
    protected string $host;
    protected string $user;
    protected string $pass;
    protected int $port;
    protected int $timeout;
    protected int $cacheTtl;

    public function __construct()
    {
        $this->host     = config('mikrotik.host', '42.62.176.169');
        $this->user     = config('mikrotik.user', 'dwi1234');
        $this->pass     = config('mikrotik.pass', 'dwi1234');
        $this->port     = (int) config('mikrotik.port', 8728);
        $this->timeout  = (int) config('mikrotik.timeout', 5);
        $this->cacheTtl = (int) config('mikrotik.cache_ttl', 30);
    }

    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * Mengambil data DHCP Leases untuk monitoring Hub.
     * Mengutamakan data Database Lokal (hasil sinkronisasi berkala Cron Job per 1 jam)
     * agar loading sangat cepat, stabil, dan tidak bergantung koneksi lambat/fluktuasi router.
     *
     * Jika $forceFresh === true (misal user klik "Refresh Live Data"), sistem akan
     * menarik data live langsung dari Router MikroTik dan mengupdate database lokal.
     */
    public function getDhcpLeasesHub(bool $forceFresh = false): array
    {
        $dbCount = MikrotikDevice::count();

        // Jika forceFresh diminta ATAU database lokal masih kosong sama sekali, tarik dari router
        if ($forceFresh || $dbCount === 0) {
            $syncResult = $this->syncAllDevicesToDatabase();
            if (!$syncResult['success'] && $dbCount === 0) {
                return [
                    'is_connected'  => false,
                    'error_message' => $syncResult['message'] ?? 'Gagal terhubung ke router MikroTik.',
                    'last_sync'     => Carbon::now()->format('H:i:s'),
                    'host'          => $this->host,
                    'from_cache'    => false,
                    'stats'         => [
                        'total'   => 0,
                        'bound'   => 0,
                        'dynamic' => 0,
                        'waiting' => 0,
                        'static'  => 0,
                    ],
                    'leases'        => [],
                ];
            }
        }

        // Ambil data dari database lokal yang tersimpan
        $dbDevices = MikrotikDevice::all();
        $latestUpdated = MikrotikDevice::max('updated_at');
        $lastSyncTime = $latestUpdated ? Carbon::parse($latestUpdated)->format('H:i:s') : Carbon::now()->format('H:i:s');

        $leases = $dbDevices->map(function ($d) {
            $isDynamic = strtolower($d->device_type ?? 'dynamic') === 'dynamic';
            $status = strtolower($d->status ?? ($d->is_active ? 'bound' : 'waiting'));
            $expires = $d->expires_after ?: ($isDynamic ? '-' : 'Static');

            return [
                'ip_address'    => $d->ip_address ?? '-',
                'mac_address'   => strtoupper($d->mac_address),
                'host_name'     => $d->host_name ?: 'Unknown Device',
                'device_type'   => $isDynamic ? 'DYNAMIC' : 'STATIC',
                'status'        => $status,
                'expires_after' => $expires,
                'comment'       => $d->comment ?: '',
                'is_active'     => (bool) $d->is_active,
                'server'        => $d->interface ?: '',
            ];
        })->toArray();

        $stats = $this->calculateStats($leases);

        return [
            'is_connected'  => true,
            'error_message' => null,
            'last_sync'     => $lastSyncTime,
            'host'          => $this->host,
            'from_cache'    => false,
            'stats'         => $stats,
            'leases'        => $leases,
        ];
    }

    /**
     * Hitung metrik statistik dari daftar leases
     */
    protected function calculateStats(array $leases): array
    {
        $total   = count($leases);
        $bound   = 0;
        $dynamic = 0;
        $waiting = 0;
        $static  = 0;

        foreach ($leases as $l) {
            $status = strtolower($l['status'] ?? '');
            $type   = strtoupper($l['device_type'] ?? '');

            if ($status === 'bound') {
                $bound++;
            } elseif ($status === 'waiting' || $status === 'offered') {
                $waiting++;
            }

            if ($type === 'DYNAMIC') {
                $dynamic++;
            } elseif ($type === 'STATIC') {
                $static++;
            }
        }

        return [
            'total'   => $total,
            'bound'   => $bound,
            'dynamic' => $dynamic,
            'waiting' => $waiting,
            'static'  => $static,
        ];
    }

    /**
     * Mengambil dan menggabungkan semua data (DHCP, ARP, Hotspot) dan simpan ke database
     */
    public function syncAllDevicesToDatabase(): array
    {
        $api = new RouterosAPI();
        $api->timeout = $this->timeout;
        if (!$api->connect($this->host, $this->user, $this->pass, $this->port)) {
            Log::error("MikrotikService sync error: " . $api->last_error);
            return [
                'success' => false,
                'message' => $api->last_error ?: 'Gagal terhubung ke router MikroTik',
                'synced'  => 0,
            ];
        }

        // 1. Tarik DHCP Leases
        $api->write('/ip/dhcp-server/lease/print');
        $rawDhcp = $api->read();

        // 2. Tarik ARP Table
        $api->write('/ip/arp/print');
        $rawArp = $api->read();

        // 3. Tarik Hotspot Hosts
        $api->write('/ip/hotspot/host/print');
        $rawHotspot = $api->read();

        $api->disconnect();

        $devices = [];
        $now = Carbon::now();

        // Parse DHCP
        foreach ($rawDhcp as $d) {
            $mac = strtoupper($d['mac-address'] ?? '');
            if (!$mac) continue;

            $status = strtolower($d['status'] ?? 'waiting');
            $isDynamic = ($d['dynamic'] ?? 'false') === 'true';
            $disabled = ($d['disabled'] ?? 'false') === 'true';
            $isActive = ($status === 'bound' && !$disabled);

            $devices[$mac] = [
                'mac_address'   => $mac,
                'ip_address'    => $d['address'] ?? null,
                'host_name'     => $d['host-name'] ?? ($d['comment'] ?? null),
                'device_type'   => $isDynamic ? 'dynamic' : 'static',
                'status'        => $status,
                'is_active'     => $isActive,
                'source'        => 'DHCP Lease',
                'expires_after' => $d['expires-after'] ?? ($isDynamic ? null : 'Static'),
                'comment'       => $d['comment'] ?? null,
                'last_seen_at'  => $isActive ? $now : null,
                'raw_payload'   => json_encode($d),
            ];
        }

        // Parse ARP
        foreach ($rawArp as $a) {
            $mac = strtoupper($a['mac-address'] ?? '');
            if (!$mac) continue;

            $disabled = ($a['disabled'] ?? 'false') === 'true';
            $invalid  = ($a['invalid'] ?? 'false') === 'true';
            $complete = ($a['complete'] ?? 'true') === 'true';
            $isActive = (!$disabled && !$invalid && $complete);

            if (isset($devices[$mac])) {
                if ($isActive) {
                    $devices[$mac]['is_active'] = true;
                    $devices[$mac]['last_seen_at'] = $now;
                }
                if (empty($devices[$mac]['comment']) && !empty($a['comment'])) {
                    $devices[$mac]['comment'] = $a['comment'];
                }
                $devices[$mac]['interface'] = $a['interface'] ?? null;
            } else {
                $devices[$mac] = [
                    'mac_address'   => $mac,
                    'ip_address'    => $a['address'] ?? null,
                    'host_name'     => $a['comment'] ?? null,
                    'device_type'   => 'dynamic',
                    'status'        => $isActive ? 'bound' : 'waiting',
                    'is_active'     => $isActive,
                    'source'        => 'ARP Table',
                    'interface'     => $a['interface'] ?? null,
                    'comment'       => $a['comment'] ?? null,
                    'last_seen_at'  => $isActive ? $now : null,
                    'raw_payload'   => json_encode($a),
                ];
            }
        }

        // Parse Hotspot
        foreach ($rawHotspot as $h) {
            $mac = strtoupper($h['mac-address'] ?? '');
            if (!$mac) continue;

            $authorized = ($h['authorized'] ?? 'false') === 'true';
            $bypassed   = ($h['bypassed'] ?? 'false') === 'true';
            $isActive   = ($authorized || $bypassed);

            if (isset($devices[$mac])) {
                if ($isActive) {
                    $devices[$mac]['is_active'] = true;
                    $devices[$mac]['last_seen_at'] = $now;
                }
            } else {
                $devices[$mac] = [
                    'mac_address'   => $mac,
                    'ip_address'    => $h['address'] ?? null,
                    'host_name'     => $h['user'] ?? null,
                    'device_type'   => 'dynamic',
                    'status'        => $isActive ? 'bound' : 'waiting',
                    'is_active'     => $isActive,
                    'source'        => 'Hotspot Host',
                    'last_seen_at'  => $isActive ? $now : null,
                    'raw_payload'   => json_encode($h),
                ];
            }
        }

        // Simpan / Upsert ke Database dengan chunking cepat (High Performance)
        $syncedCount = 0;
        $deviceChunks = array_chunk(array_values($devices), 300);
        foreach ($deviceChunks as $chunk) {
            \Illuminate\Support\Facades\DB::transaction(function () use ($chunk, &$syncedCount) {
                foreach ($chunk as $dev) {
                    MikrotikDevice::updateOrCreate(
                        ['mac_address' => $dev['mac_address']],
                        [
                            'ip_address'    => $dev['ip_address'],
                            'host_name'     => $dev['host_name'],
                            'device_type'   => $dev['device_type'] ?? 'dynamic',
                            'status'        => $dev['status'] ?? 'bound',
                            'is_active'     => $dev['is_active'],
                            'source'        => $dev['source'] ?? 'DHCP Lease',
                            'expires_after' => $dev['expires_after'] ?? null,
                            'interface'     => $dev['interface'] ?? null,
                            'comment'       => $dev['comment'] ?? null,
                            'last_seen_at'  => $dev['last_seen_at'] ?? null,
                            'raw_payload'   => $dev['raw_payload'] ?? null,
                        ]
                    );
                    $syncedCount++;
                }
            });
        }

        // Invalidate DHCP cache agar sinkronisasi terbaru tampil
        Cache::forget("mikrotik_dhcp_hub_{$this->host}");

        return [
            'success' => true,
            'message' => "Berhasil menyinkronkan {$syncedCount} perangkat dari MikroTik ke database.",
            'synced'  => $syncedCount,
        ];
    }

    /**
     * Ambil daftar interface aktif di Router MikroTik
     */
    public function getInterfaces(): array
    {
        $cached = Cache::get("mikrotik_interfaces_{$this->host}");
        if (is_array($cached) && !empty($cached)) {
            return $cached;
        }

        $api = new RouterosAPI();
        $api->timeout = 5;
        if (!$api->connect($this->host, $this->user, $this->pass, $this->port)) {
            return [];
        }

        $api->write('/interface/print');
        $rawInterfaces = $api->read();
        $api->disconnect();

        $interfaces = [];
        foreach ($rawInterfaces as $iface) {
            if (($iface['disabled'] ?? 'false') === 'true') {
                continue;
            }
            $interfaces[] = [
                'name'    => $iface['name'] ?? '',
                'type'    => $iface['type'] ?? 'ether',
                'running' => ($iface['running'] ?? 'true') === 'true',
                'comment' => $iface['comment'] ?? '',
            ];
        }

        if (!empty($interfaces)) {
            Cache::put("mikrotik_interfaces_{$this->host}", $interfaces, 300);
        }

        return $interfaces;
    }

    /**
     * Ambil realtime monitoring traffic (Rx/Tx) untuk interface tertentu
     */
    public function getInterfaceTraffic(string $interface): array
    {
        $api = new RouterosAPI();
        $api->timeout = 4;
        if (!$api->connect($this->host, $this->user, $this->pass, $this->port)) {
            return [
                'success' => false,
                'message' => $api->last_error ?: 'Gagal terhubung ke router MikroTik',
            ];
        }

        $api->write('/interface/monitor-traffic', false);
        $api->write('=interface=' . $interface, false);
        $api->write('=once=');

        $read = $api->read();
        $api->disconnect();

        if (empty($read) || !isset($read[0])) {
            return [
                'success' => false,
                'message' => 'Tidak ada data traffic yang diterima untuk interface ' . $interface,
            ];
        }

        $data = $read[0];
        $rxBps = (int) ($data['rx-bits-per-second'] ?? 0);
        $txBps = (int) ($data['tx-bits-per-second'] ?? 0);
        $rxPps = (int) ($data['rx-packets-per-second'] ?? 0);
        $txPps = (int) ($data['tx-packets-per-second'] ?? 0);

        return [
            'success'      => true,
            'interface'    => $interface,
            'timestamp'    => Carbon::now()->format('H:i:s'),
            'time_ms'      => (int) (microtime(true) * 1000),
            'rx_bps'       => $rxBps,
            'tx_bps'       => $txBps,
            'rx_kbps'      => round($rxBps / 1000, 2),
            'tx_kbps'      => round($txBps / 1000, 2),
            'rx_mbps'      => round($rxBps / 1000000, 2),
            'tx_mbps'      => round($txBps / 1000000, 2),
            'rx_formatted' => $this->formatBits($rxBps),
            'tx_formatted' => $this->formatBits($txBps),
            'rx_pps'       => $rxPps,
            'tx_pps'       => $txPps,
        ];
    }

    /**
     * Format bits per second ke string yang mudah dibaca (bps, Kbps, Mbps, Gbps)
     */
    protected function formatBits(int $bits): string
    {
        if ($bits >= 1000000000) {
            return round($bits / 1000000000, 2) . ' Gbps';
        }
        if ($bits >= 1000000) {
            return round($bits / 1000000, 2) . ' Mbps';
        }
        if ($bits >= 1000) {
            return round($bits / 1000, 1) . ' Kbps';
        }
        return $bits . ' bps';
    }

    /**
     * Ambil informasi System Resource Router MikroTik (/system/resource/print & /system/identity/print)
     */
    public function getSystemResource(): array
    {
        $api = new RouterosAPI();
        $api->timeout = 5;
        if (!$api->connect($this->host, $this->user, $this->pass, $this->port)) {
            return [
                'success' => false,
                'message' => $api->last_error ?: 'Gagal terhubung ke router MikroTik',
            ];
        }

        // 1. Ambil /system/resource/print
        $api->write('/system/resource/print');
        $rawRes = $api->read();

        // 2. Ambil /system/identity/print
        $api->write('/system/identity/print');
        $rawId = $api->read();

        $api->disconnect();

        $res = $rawRes[0] ?? [];
        $identity = $rawId[0]['name'] ?? 'MikroTik Router';

        $totalMem = (int) ($res['total-memory'] ?? 0);
        $freeMem  = (int) ($res['free-memory'] ?? 0);
        $usedMem  = max(0, $totalMem - $freeMem);
        $memPct   = $totalMem > 0 ? round(($usedMem / $totalMem) * 100, 1) : 0;

        $totalHdd = (int) ($res['total-hdd-space'] ?? 0);
        $freeHdd  = (int) ($res['free-hdd-space'] ?? 0);
        $usedHdd  = max(0, $totalHdd - $freeHdd);
        $hddPct   = $totalHdd > 0 ? round(($usedHdd / $totalHdd) * 100, 1) : 0;

        $cpuLoad = (int) ($res['cpu-load'] ?? 0);

        return [
            'success'            => true,
            'router_name'        => $identity,
            'host'               => $this->host,
            'uptime'             => $res['uptime'] ?? '-',
            'version'            => $res['version'] ?? '-',
            'board_name'         => $res['board-name'] ?? ($res['platform'] ?? 'MikroTik'),
            'architecture_name'  => $res['architecture-name'] ?? '-',
            'cpu'                => $res['cpu'] ?? 'MIPS/ARM',
            'cpu_count'          => (int) ($res['cpu-count'] ?? 1),
            'cpu_frequency'      => (int) ($res['cpu-frequency'] ?? 0),
            'cpu_load'           => $cpuLoad,
            'total_memory'       => $totalMem,
            'free_memory'        => $freeMem,
            'used_memory'        => $usedMem,
            'memory_percent'     => $memPct,
            'memory_formatted'   => $this->formatBytes($usedMem) . ' / ' . $this->formatBytes($totalMem),
            'total_hdd'          => $totalHdd,
            'free_hdd'           => $freeHdd,
            'used_hdd'           => $usedHdd,
            'hdd_percent'        => $hddPct,
            'hdd_formatted'      => $this->formatBytes($usedHdd) . ' / ' . $this->formatBytes($totalHdd),
            'updated_at'         => Carbon::now()->format('H:i:s'),
        ];
    }

    /**
     * Format bytes ke string yang mudah dibaca (B, KB, MB, GB)
     */
    public function formatBytes(int $bytes): string
    {
        if ($bytes >= 1073741824) {
            return round($bytes / 1073741824, 2) . ' GB';
        }
        if ($bytes >= 1048576) {
            return round($bytes / 1048576, 1) . ' MB';
        }
        if ($bytes >= 1024) {
            return round($bytes / 1024, 1) . ' KB';
        }
        return $bytes . ' B';
    }

    /**
     * Reboot Router MikroTik secara jarak jauh (/system/reboot)
     */
    public function rebootRouter(): array
    {
        $api = new RouterosAPI();
        $api->timeout = 5;
        if (!$api->connect($this->host, $this->user, $this->pass, $this->port)) {
            return [
                'success' => false,
                'message' => $api->last_error ?: 'Gagal terhubung ke router MikroTik',
            ];
        }

        try {
            // Tulis perintah reboot ke router
            $api->write('/system/reboot');
            // Catatan: RouterOS langsung menutup socket begitu reboot dimulai
            @$api->disconnect();
        } catch (\Throwable $e) {
            Log::info("Mikrotik reboot triggered (socket closed gracefully): " . $e->getMessage());
        }

        // Hapus cache yang tersimpan
        Cache::forget("mikrotik_dhcp_hub_{$this->host}");
        Cache::forget("mikrotik_interfaces_{$this->host}");

        Log::warning("MikroTik Router ({$this->host}) was rebooted by user via dashboard.");

        return [
            'success' => true,
            'message' => "Perintah reboot berhasil dikirim ke router ({$this->host}). Router sedang memulai ulang.",
        ];
    }

    /**
     * Ambil pembaruan delta DHCP Leases langsung dari MikroTik secara ultra-ringan (Live Stream 3s)
     */
    public function getLiveDeltaUpdates(): array
    {
        $cacheKey = "mikrotik_dhcp_snapshot_{$this->host}";
        $previousSnapshot = Cache::get($cacheKey, []);

        $api = new RouterosAPI();
        $api->timeout = 2; // Fast timeout 2 detik untuk responsiveness
        if (!$api->connect($this->host, $this->user, $this->pass, $this->port)) {
            return [
                'has_updates' => false,
                'connected'   => false,
                'updated'     => [],
                'stats'       => null,
                'last_check'  => Carbon::now()->format('H:i:s'),
            ];
        }

        // Query spesifik hanya kolom DHCP yang dibutuhkan (.proplist)
        $api->write('/ip/dhcp-server/lease/print', false);
        $api->write('=.proplist=.id,address,mac-address,host-name,status,expires-after,dynamic,disabled,comment,server');
        $rawDhcp = $api->read();
        $api->disconnect();

        if (!is_array($rawDhcp)) {
            return [
                'has_updates' => false,
                'connected'   => true,
                'updated'     => [],
                'stats'       => null,
                'last_check'  => Carbon::now()->format('H:i:s'),
            ];
        }

        $currentSnapshot = [];
        $updatedItems = [];
        $now = Carbon::now();

        foreach ($rawDhcp as $d) {
            $mac = strtoupper($d['mac-address'] ?? '');
            if (!$mac) continue;

            $status = strtolower($d['status'] ?? 'waiting');
            $isDynamic = ($d['dynamic'] ?? 'false') === 'true';
            $disabled = ($d['disabled'] ?? 'false') === 'true';
            $isActive = ($status === 'bound' && !$disabled);
            $expires = $d['expires-after'] ?? ($isDynamic ? '-' : 'Static');
            $ip = $d['address'] ?? '-';
            $host = $d['host-name'] ?? ($d['comment'] ?? 'Unknown Device');
            $comment = $d['comment'] ?? '';

            $itemData = [
                'ip_address'    => $ip,
                'mac_address'   => $mac,
                'host_name'     => $host,
                'device_type'   => $isDynamic ? 'DYNAMIC' : 'STATIC',
                'status'        => $status,
                'expires_after' => $expires,
                'comment'       => $comment,
                'is_active'     => $isActive,
                'server'        => $d['server'] ?? '',
            ];

            $currentSnapshot[$mac] = $itemData;

            // Periksa apakah item baru atau ada perubahan status/ip/expires dari snapshot sebelumnya
            if (!isset($previousSnapshot[$mac])) {
                $updatedItems[] = $itemData;
            } else {
                $prev = $previousSnapshot[$mac];
                if (
                    $prev['status'] !== $status ||
                    $prev['ip_address'] !== $ip ||
                    $prev['host_name'] !== $host ||
                    $prev['device_type'] !== $itemData['device_type'] ||
                    $prev['expires_after'] !== $expires
                ) {
                    $updatedItems[] = $itemData;
                }
            }
        }

        // Simpan snapshot saat ini ke cache (TTL 120 detik)
        Cache::put($cacheKey, $currentSnapshot, 120);

        // Jika ada perubahan, update database MikrotikDevice
        if (!empty($updatedItems)) {
            foreach ($updatedItems as $dev) {
                MikrotikDevice::updateOrCreate(
                    ['mac_address' => $dev['mac_address']],
                    [
                        'ip_address'    => $dev['ip_address'],
                        'host_name'     => $dev['host_name'],
                        'device_type'   => strtolower($dev['device_type']),
                        'status'        => $dev['status'],
                        'is_active'     => $dev['is_active'],
                        'expires_after' => $dev['expires_after'] !== 'Static' ? $dev['expires_after'] : null,
                        'comment'       => $dev['comment'],
                        'interface'     => $dev['server'] ?: null,
                        'last_seen_at'  => $dev['is_active'] ? $now : null,
                    ]
                );
            }
        }

        $stats = $this->calculateStats(array_values($currentSnapshot));

        return [
            'has_updates' => !empty($updatedItems),
            'connected'   => true,
            'count'       => count($updatedItems),
            'updated'     => $updatedItems,
            'stats'       => $stats,
            'last_check'  => $now->format('H:i:s'),
        ];
    }

    /**
     * Ambil pembaruan delta perangkat yang berubah sejak waktu tertentu
     */
    public function getDeltaUpdates(?string $since = null): array
    {
        return $this->getLiveDeltaUpdates();
    }
}

