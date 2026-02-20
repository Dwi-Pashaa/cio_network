<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\WablasReport;

class FetchWablasReport extends Command
{
    protected $signature = 'wablas:fetch';
    protected $description = 'Fetch Wablas report (only pending status)';

    public function handle()
    {
        $this->info('Start fetching pending messages...');

        $page = 1;
        $totalPage = 1;
        $totalInserted = 0;

        try {

            do {

                $this->info("Fetching page {$page}...");

                $response = Http::withHeaders([
                    'Authorization' => config('wablas.token') . '.' . config('wablas.secret_key'),
                ])
                    ->timeout(60)
                    ->retry(3, 1000)
                    ->get('https://kudus.wablas.com/api/report/message', [
                        'perPage' => 500,
                        'page'    => $page,
                    ]);

                if (!$response->successful()) {
                    $this->error("API failed at page {$page}");
                    break;
                }

                $result = $response->json();

                $messages  = $result['message'] ?? [];
                $totalPage = $result['totalPage'] ?? 1;

                foreach ($messages as $row) {

                    // Cek dulu apakah data sudah ada dan sent_at belum null
                    $existing = WablasReport::where('wablas_id', $row['id'])->first();

                    if ($existing && $existing->sent_at !== null) {
                        // Jika sudah ada dan sent_at ada, skip update
                        continue;
                    }

                    WablasReport::updateOrCreate(
                        ['wablas_id' => $row['id']],
                        [
                            'from'    => $row['phone']['from'] ?? null,
                            'to'      => $row['phone']['to'] ?? null,
                            'status'  => $row['status'] ?? null,
                            'message' => $row['text'] ?? null,
                            'date'    => $row['date']['created_at'] ?? null,
                        ]
                    );

                    $totalInserted++;
                }

                $this->info("Page {$page} selesai. Pending masuk: {$totalInserted}");

                $page++;
            } while ($page <= $totalPage);

            $this->info("Selesai sync pending.");
            $this->info("Total pending diproses: {$totalInserted}");
        } catch (\Exception $e) {

            $this->error('Error: ' . $e->getMessage());
        }
    }
}
