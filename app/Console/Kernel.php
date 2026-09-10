<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Backup bulanan
        $schedule->command('backup:monthly')
            ->monthlyOn(1, '02:00')
            ->withoutOverlapping();

        // Fetch Wablas tiap 10 menit
        $schedule->command('wablas:fetch')
            ->everyTenMinutes()
            ->withoutOverlapping();

        // Sync data perangkat MikroTik berkala tiap 1 jam
        $schedule->command('mikrotik:sync')
            ->hourly()
            ->withoutOverlapping();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
