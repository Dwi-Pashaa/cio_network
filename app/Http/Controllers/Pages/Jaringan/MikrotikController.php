<?php

namespace App\Http\Controllers\Pages\Jaringan;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Services\MikrotikService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MikrotikController extends Controller
{
    protected MikrotikService $mikrotikService;

    public function __construct(MikrotikService $mikrotikService)
    {
        $this->mikrotikService = $mikrotikService;
    }

    /**
     * Tampilkan Halaman Monitoring MikroTik DHCP Hub
     */
    public function index()
    {
        $hubData = $this->mikrotikService->getDhcpLeasesHub(false);
        $interfaces = $this->mikrotikService->getInterfaces();
        $systemResource = $this->mikrotikService->getSystemResource();

        return view('pages.mikrotik.dhcp-hub', [
            'stats'           => $hubData['stats'],
            'leases'          => $hubData['leases'],
            'last_sync'       => $hubData['last_sync'],
            'host'            => $hubData['host'],
            'is_connected'    => $hubData['is_connected'],
            'error_message'   => $hubData['error_message'] ?? null,
            'interfaces'      => $interfaces,
            'system_resource' => $systemResource,
        ]);
    }

    /**
     * Endpoint API JSON untuk mendapatkan data System Resource Router (/system/resource/print)
     */
    public function getSystemResource(): JsonResponse
    {
        $resource = $this->mikrotikService->getSystemResource();
        return response()->json($resource);
    }

    /**
     * Endpoint API JSON untuk mendapatkan daftar Interface
     */
    public function getInterfaces(): JsonResponse
    {
        $interfaces = $this->mikrotikService->getInterfaces();
        return response()->json([
            'success'    => true,
            'interfaces' => $interfaces,
        ]);
    }

    /**
     * Endpoint API JSON untuk realtime monitoring traffic Rx/Tx
     */
    public function getTraffic(Request $request): JsonResponse
    {
        $interface = trim($request->input('interface', ''));
        if (empty($interface)) {
            $interfaces = $this->mikrotikService->getInterfaces();
            $interface = !empty($interfaces) ? $interfaces[0]['name'] : 'ether1';
        }

        $traffic = $this->mikrotikService->getInterfaceTraffic($interface);

        return response()->json($traffic);
    }

    /**
     * Endpoint API JSON untuk AJAX Live Sync & Refresh
     */
    public function getDhcpData(Request $request): JsonResponse
    {
        $forceFresh = $request->boolean('force', false);
        $hubData = $this->mikrotikService->getDhcpLeasesHub($forceFresh);

        return response()->json([
            'success'       => true,
            'is_connected'  => $hubData['is_connected'],
            'error_message' => $hubData['error_message'] ?? null,
            'last_sync'     => $hubData['last_sync'],
            'host'          => $hubData['host'],
            'from_cache'    => $hubData['from_cache'] ?? false,
            'stats'         => $hubData['stats'],
            'leases'        => $hubData['leases'],
        ]);
    }

    /**
     * Cari detail data pelanggan berdasarkan MAC Address
     */
    public function getCustomerByMac(Request $request): JsonResponse
    {
        $mac = strtoupper(trim($request->input('mac', '')));

        if (empty($mac)) {
            return response()->json([
                'success' => false,
                'found'   => false,
                'message' => 'MAC Address tidak boleh kosong.'
            ], 400);
        }

        $customer = Customer::whereRaw('UPPER(TRIM(mac_address)) = ?', [$mac])
            ->with([
                'paket',
                'price',
                'router',
                'odc',
                'odp',
                'olt',
                'vlan',
                'regencie',
                'district',
                'village',
                'hometown',
                'rt',
                'rw',
                'type',
                'tipePelanggan',
                'organization'
            ])
            ->first();

        if (!$customer) {
            return response()->json([
                'success'     => true,
                'found'       => false,
                'mac_address' => $mac,
                'message'     => 'Perangkat dengan MAC Address ini belum terdaftar di data pelanggan.'
            ]);
        }

        return response()->json([
            'success'     => true,
            'found'       => true,
            'mac_address' => $mac,
            'customer'    => [
                'id'             => $customer->id,
                'name'           => $customer->name,
                'telp'           => $customer->telp,
                'email'          => $customer->email,
                'nik'            => $customer->nik,
                'status'         => $customer->status,
                'mac_address'    => $customer->mac_address,
                'name_wifi'      => $customer->name_wifi ?: '-',
                'password_wifi'  => $customer->password_wifi ?: '-',
                'pppoe_username' => $customer->pppoe_username ?: '-',
                'paket_name'     => $customer->paket?->name ?: '-',
                'price_amount'   => $customer->price?->price ? 'Rp ' . number_format($customer->price->price, 0, ',', '.') : '-',
                'type_name'      => $customer->type?->name ?: ($customer->tipePelanggan?->name ?: '-'),
                'router_name'    => $customer->router?->name ?: '-',
                'router_ip'      => $customer->router?->ip ?: '-',
                'olt_name'       => $customer->olt?->name ?: '-',
                'odc_name'       => $customer->odc?->name ?: '-',
                'odp_name'       => $customer->odp?->name ?: '-',
                'vlan_name'      => $customer->vlan?->name ?: '-',
                'address'        => implode(', ', array_filter([
                    $customer->hometown?->name,
                    $customer->rt?->name ? 'RT ' . $customer->rt->name : null,
                    $customer->rw?->name ? 'RW ' . $customer->rw->name : null,
                    $customer->village?->name,
                    $customer->district?->name,
                    $customer->regencie?->name,
                ])) ?: 'Alamat belum diatur',
                'latitude'       => $customer->latitude,
                'longitude'      => $customer->longitude,
                'organization'   => $customer->organization?->name ?: '-',
                'edit_url'       => route('customer.edit', $customer->id),
            ]
        ]);
    }

    /**
     * Trigger sinkronisasi data lengkap ke database
     */
    public function syncDatabase(Request $request): JsonResponse
    {
        $result = $this->mikrotikService->syncAllDevicesToDatabase();

        return response()->json($result);
    }

    /**
     * Trigger reboot router MikroTik secara jarak jauh
     */
    public function reboot(Request $request): JsonResponse
    {
        $result = $this->mikrotikService->rebootRouter();

        return response()->json($result);
    }

    /**
     * Endpoint Server-Sent Events (SSE) untuk Real-Time Delta Streaming
     */
    public function stream(Request $request): StreamedResponse
    {
        $response = new StreamedResponse(function () use ($request) {
            // Nonaktifkan buffering output agar stream langsung terkirim
            if (ob_get_level()) {
                ob_end_clean();
            }

            $lastChecked = $request->input('since', Carbon::now()->subSeconds(5)->toDateTimeString());

            // Kirim event pembukaan stream
            echo "event: connected\n";
            echo "data: " . json_encode([
                'status' => 'connected',
                'time'   => Carbon::now()->format('H:i:s'),
            ]) . "\n\n";
            flush();

            $iteration = 0;
            while (!connection_aborted()) {
                $iteration++;
                $delta = $this->mikrotikService->getDeltaUpdates($lastChecked);

                if ($delta['has_updates']) {
                    $lastChecked = $delta['last_check'];
                    echo "event: delta\n";
                    echo "data: " . json_encode($delta) . "\n\n";
                    flush();
                } else {
                    // Kirim heartbeat ringan setiap 6 detik
                    if ($iteration % 2 === 0) {
                        echo ": ping\n\n";
                        flush();
                    }
                }

                sleep(3);
            }
        });

        $response->headers->set('Content-Type', 'text/event-stream');
        $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate');
        $response->headers->set('Connection', 'keep-alive');
        $response->headers->set('X-Accel-Buffering', 'no');

        return $response;
    }
}
