<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class MonthlyDatabaseBackup extends Command
{
    protected $signature = 'backup:monthly';
    protected $description = 'Export database dan kirim ke email';

    public function handle()
    {
        $fileName = 'backup_' . now()->format('Y-m-d_H-i') . '.sql';
        $dir = storage_path('app/backups');
        $path = $dir . '/' . $fileName;

        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        $db = config('database.connections.mysql');

        $command = sprintf(
            'mysqldump --protocol=tcp --skip-ssl --no-tablespaces -h%s -P%s -u%s -p%s %s > %s 2>&1',
            escapeshellarg($db['host']),
            escapeshellarg($db['port']),
            escapeshellarg($db['username']),
            escapeshellarg($db['password']),
            escapeshellarg($db['database']),
            escapeshellarg($path)
        );

        exec($command, $output, $status);

        if ($status !== 0 || !file_exists($path) || filesize($path) === 0) {
            Log::error('Backup DB gagal', compact('output', 'status', 'path'));
            $this->error('Backup gagal — cek log.');
            return 1;
        }

        try {
            Mail::raw("Berikut backup database bulan. " . Carbon::now()->format('d-M-Y H:i:s') . "", function ($message) use ($path, $fileName) {
                $message->to('dwipasha336@gmail.com')
                    ->subject('Backup Database Web Data CIO Network Bulanan')
                    ->attach($path);
            });

            $this->info('Backup selesai & email terkirim.');
        } catch (\Throwable $e) {
            Log::error('Gagal kirim email backup', ['error' => $e->getMessage()]);
            $this->error('Backup berhasil, tapi email gagal dikirim.');
        }

        return 0;
    }
}
