<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FontePhoneCheckService
{
    protected string $token;
    protected string $apiUrl = 'https://api.fonnte.com/validate';

    public function __construct()
    {
        $this->token = config('services.fonnte.token');
    }

    /**
     * Normalisasi nomor telepon ke format 628xxx.
     * Input yang didukung: 08xxx, 8xxx, +628xxx, 628xxx
     */
    public function normalize(string $phone): string
    {
        // Hapus semua karakter non-digit
        $phone = preg_replace('/\D/', '', $phone);

        if (str_starts_with($phone, '08')) {
            return '62' . substr($phone, 1);
        }

        if (str_starts_with($phone, '8')) {
            return '62' . $phone;
        }

        // Sudah dalam format 628xxx atau format lain, kembalikan apa adanya
        return $phone;
    }

    /**
     * Cek apakah nomor terdaftar di WhatsApp via Fonnte API.
     *
     * Response Fonnte validate (format baru):
     * {
     *   "status": true,
     *   "registered": ["628123456789"],
     *   "not_registered": ["628987654321"],
     *   "issues": []
     * }
     *
     * Response Fonnte validate (format lama/fallback):
     * {
     *   "status": true,
     *   "target": [
     *     { "number": "628xxx", "status": true, "name": "..." }
     *   ]
     * }
     *
     * @return array{is_valid: bool, status: string, message: string, raw_response: array|null}
     */
    public function check(string $phone): array
    {
        $normalized = $this->normalize($phone);

        try {
            $response = Http::timeout(15)
                ->withHeaders([
                    'Authorization' => $this->token,
                ])
                ->asForm()
                ->post($this->apiUrl, [
                    'target'      => $normalized,
                    'countryCode' => '62',
                ]);

            if (!$response->successful()) {
                Log::warning('FontePhoneCheck: HTTP error', [
                    'status' => $response->status(),
                    'phone'  => $normalized,
                    'body'   => $response->body(),
                ]);

                return $this->fail('Gagal mengecek nomor. Silakan coba beberapa saat lagi.');
            }

            $data = $response->json();

            // --- Cek status level atas ---
            if (($data['status'] ?? false) === false) {
                $reason = $data['reason'] ?? '';

                // Device Fonnte disconnected — izinkan lewat agar teknisi tidak terblokir
                if (
                    str_contains(strtolower($reason), 'disconnect') ||
                    str_contains(strtolower($reason), 'device')
                ) {
                    Log::warning('FontePhoneCheck: Device disconnected, validation skipped', [
                        'phone'  => $normalized,
                        'reason' => $reason,
                    ]);

                    return $this->fail('Gagal mengecek nomor karena perangkat Fonnte offline.');
                }

                Log::warning('FontePhoneCheck: API returned status false', [
                    'phone'    => $normalized,
                    'response' => $data,
                ]);

                return $this->fail('Gagal mengecek nomor. Silakan coba beberapa saat lagi.');
            }

            // --- Format baru: registered / not_registered sebagai array ---
            $registered    = $data['registered']    ?? [];
            $notRegistered = $data['not_registered'] ?? [];

            if (!empty($registered) || !empty($notRegistered)) {
                // Cek apakah nomor SPESIFIK ada di array registered
                if (in_array($normalized, $registered, true)) {
                    return [
                        'is_valid'     => true,
                        'status'       => 'registered',
                        'message'      => 'Nomor WhatsApp valid dan terdaftar.',
                        'raw_response' => $data,
                    ];
                }

                // Cek apakah nomor SPESIFIK ada di array not_registered
                if (in_array($normalized, $notRegistered, true)) {
                    return [
                        'is_valid'     => false,
                        'status'       => 'not_registered',
                        'message'      => 'Nomor tidak terdaftar di WhatsApp.',
                        'raw_response' => $data,
                    ];
                }

                // Nomor tidak ditemukan di kedua array — edge case
                Log::warning('FontePhoneCheck: Number not found in registered or not_registered arrays', [
                    'phone'    => $normalized,
                    'response' => $data,
                ]);

                return $this->fail('Gagal memverifikasi status nomor. Response tidak konsisten.');
            }

            // --- Format lama / fallback: target array ---
            $targets = $data['target'] ?? $data['data'] ?? [];

            if (empty($targets) || !is_array($targets)) {
                Log::warning('FontePhoneCheck: Unrecognized response format', [
                    'phone'    => $normalized,
                    'response' => $data,
                ]);

                return $this->fail('Gagal mengecek nomor. Response tidak valid.');
            }

            $entry  = $targets[0];
            $exists = $entry['status'] ?? $entry['registered'] ?? $entry['exists'] ?? false;

            if ($exists) {
                return [
                    'is_valid'     => true,
                    'status'       => 'registered',
                    'message'      => 'Nomor WhatsApp valid dan terdaftar.',
                    'raw_response' => $data,
                ];
            }

            return [
                'is_valid'     => false,
                'status'       => 'not_registered',
                'message'      => 'Nomor tidak terdaftar di WhatsApp.',
                'raw_response' => $data,
            ];

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('FontePhoneCheck: Connection timeout/error', [
                'phone'   => $normalized,
                'message' => $e->getMessage(),
            ]);

            return $this->fail('Gagal mengecek nomor. Koneksi timeout, silakan coba beberapa saat lagi.');

        } catch (\Throwable $e) {
            Log::error('FontePhoneCheck: Unexpected error', [
                'phone'   => $normalized,
                'message' => $e->getMessage(),
            ]);

            return $this->fail('Gagal mengecek nomor. Terjadi kesalahan tidak terduga.');
        }
    }

    /**
     * Helper: kembalikan response gagal secara konsisten.
     *
     * @return array{is_valid: bool, status: string, message: string, raw_response: null}
     */
    private function fail(string $message): array
    {
        return [
            'is_valid'     => false,
            'status'       => 'check_failed',
            'message'      => $message,
            'raw_response' => null,
        ];
    }
}
