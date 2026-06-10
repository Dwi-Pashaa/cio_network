<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WablasPhoneCheckService
{
    protected string $token;
    protected string $apiUrl = 'https://phone.wablas.com/check-phone-number';

    public function __construct()
    {
        $this->token = config('wablas.token');
    }

    /**
     * Normalisasi nomor telepon ke format 628xxx.
     * Input bisa: 08xxx, 8xxx, +628xxx, 628xxx
     */
    public function normalize(string $phone): string
    {
        $phone = preg_replace('/\D/', '', $phone); // hapus semua non-digit

        if (str_starts_with($phone, '08')) {
            return '62' . substr($phone, 1);
        }

        if (str_starts_with($phone, '8')) {
            return '62' . $phone;
        }

        return $phone; // sudah 628xxx atau format lain
    }

    /**
     * Cek apakah nomor telepon terdaftar di WhatsApp via Wablas API.
     *
     * @return array{is_valid: bool, message: string, raw_response: array|null}
     */
    public function check(string $phone): array
    {
        $normalized = $this->normalize($phone);

        try {
            $response = Http::timeout(10)
                ->withHeaders([
                    'Authorization' => $this->token,
                    'url'           => $this->apiUrl,
                ])
                ->get($this->apiUrl, [
                    'phones' => $normalized,
                ]);

            if (!$response->successful()) {
                Log::warning('WablasPhoneCheck: HTTP error', [
                    'status' => $response->status(),
                    'phone'  => $normalized,
                ]);

                return $this->fail('Gagal mengecek nomor. Silakan coba beberapa saat lagi.');
            }

            $data = $response->json();

            // Response Wablas: array of phone objects
            // Contoh: [{"phone":"6281xxx","exists":true}] atau {"data":[...]}
            $phones = $data['data'] ?? $data;

            if (!is_array($phones) || empty($phones)) {
                return $this->fail('Gagal mengecek nomor. Response tidak valid.');
            }

            // Ambil entri pertama
            $entry  = is_array($phones[0]) ? $phones[0] : (array) $phones[0];
            $exists = $entry['exists'] ?? $entry['isWhatsapp'] ?? $entry['registered'] ?? null;

            if ($exists === null) {
                // Fallback: cek key lain yang mungkin dipakai Wablas
                $exists = !empty($entry['number']) || !empty($entry['jid']);
            }

            if ($exists) {
                return [
                    'is_valid'     => true,
                    'message'      => 'Nomor WhatsApp valid dan terdaftar.',
                    'raw_response' => $data,
                ];
            }

            return [
                'is_valid'     => false,
                'message'      => 'Nomor tidak terdaftar di WhatsApp.',
                'raw_response' => $data,
            ];

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('WablasPhoneCheck: Connection timeout/error', [
                'phone'   => $normalized,
                'message' => $e->getMessage(),
            ]);

            return $this->fail('Gagal mengecek nomor. Silakan coba beberapa saat lagi.');
        } catch (\Throwable $e) {
            Log::error('WablasPhoneCheck: Unexpected error', [
                'phone'   => $normalized,
                'message' => $e->getMessage(),
            ]);

            return $this->fail('Gagal mengecek nomor. Silakan coba beberapa saat lagi.');
        }
    }

    private function fail(string $message): array
    {
        return [
            'is_valid'     => false,
            'message'      => $message,
            'raw_response' => null,
        ];
    }
}
