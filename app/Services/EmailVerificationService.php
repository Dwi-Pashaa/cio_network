<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class EmailVerificationService
{
    protected string $apiKey;
    protected string $apiUrl = 'https://api.quickemailverification.com/v1/verify';

    public function __construct()
    {
        $this->apiKey = config('services.quickemailverification.key');
    }

    /**
     * Verifikasi email menggunakan QuickEmailVerification API.
     *
     * @param  string  $email
     * @return array{is_valid: bool, status: string, message: string, raw_response: array|null}
     */
    public function verify(string $email): array
    {
        try {
            $response = Http::timeout(10)->get($this->apiUrl, [
                'email'  => $email,
                'apikey' => $this->apiKey,
            ]);

            if (!$response->successful()) {
                Log::warning('QuickEmailVerification: HTTP error', [
                    'status' => $response->status(),
                    'email'  => $email,
                ]);

                return $this->fail('Gagal mengecek email. Silakan coba beberapa saat lagi.');
            }

            $data = $response->json();

            // Cek did_you_mean — mungkin ada typo domain
            if (!empty($data['did_you_mean'])) {
                return [
                    'is_valid'     => false,
                    'status'       => 'not_registered',
                    'message'      => "Email tidak valid. Mungkin maksud Anda: {$data['did_you_mean']}",
                    'raw_response' => $data,
                ];
            }

            // Tolak email disposable / temporary
            if (isset($data['disposable']) && $data['disposable'] === true) {
                return [
                    'is_valid'     => false,
                    'status'       => 'not_registered',
                    'message'      => 'Email sementara tidak diperbolehkan.',
                    'raw_response' => $data,
                ];
            }

            // Kondisi valid: success=true, result=valid, safe_to_send=true, disposable=false
            $isValid = ($data['success'] ?? false) === true
                && ($data['result'] ?? '') === 'valid'
                && ($data['safe_to_send'] ?? false) === true
                && ($data['disposable'] ?? true) === false;

            if ($isValid) {
                return [
                    'is_valid'     => true,
                    'status'       => 'registered',
                    'message'      => 'Email valid dan terdaftar.',
                    'raw_response' => $data,
                ];
            }

            return [
                'is_valid'     => false,
                'status'       => 'not_registered',
                'message'      => 'Email tidak valid atau tidak terdaftar. Silakan gunakan email Gmail yang aktif.',
                'raw_response' => $data,
            ];

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('QuickEmailVerification: Connection timeout/error', [
                'email'   => $email,
                'message' => $e->getMessage(),
            ]);

            return $this->fail('Gagal mengecek email. Silakan coba beberapa saat lagi.');
        } catch (\Throwable $e) {
            Log::error('QuickEmailVerification: Unexpected error', [
                'email'   => $email,
                'message' => $e->getMessage(),
            ]);

            return $this->fail('Gagal mengecek email. Silakan coba beberapa saat lagi.');
        }
    }

    private function fail(string $message): array
    {
        return [
            'is_valid'     => false,
            'status'       => 'not_registered',
            'message'      => $message,
            'raw_response' => null,
        ];
    }
}
