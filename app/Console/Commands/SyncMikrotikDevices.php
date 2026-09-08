<?php

namespace App\Console\Commands;

use App\Services\MikrotikService;
use Illuminate\Console\Command;

class SyncMikrotikDevices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mikrotik:sync {--force : Paksa refresh tanpa cache}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sinkronisasi data perangkat (DHCP, ARP, Hotspot) dari MikroTik RouterOS ke database';

    /**
     * Execute the console command.
     */
    public function handle(MikrotikService $mikrotikService): int
    {
        $this->info("Memulai sinkronisasi data dari router MikroTik ({$mikrotikService->getHost()})...");

        $startTime = microtime(true);
        $result = $mikrotikService->syncAllDevicesToDatabase();
        $duration = round(microtime(true) - $startTime, 2);

        if ($result['success']) {
            $this->info("✅ " . $result['message'] . " (Waktu: {$duration}s)");
            return Command::SUCCESS;
        }

        $this->error("❌ Gagal sinkronisasi: " . $result['message']);
        return Command::FAILURE;
    }
}
