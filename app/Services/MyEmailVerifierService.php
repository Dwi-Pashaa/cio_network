<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MyEmailVerifierService
{
    protected string $apiKey;
    protected string $apiUrl = 'https://api.myemailverifier.com/api/validate_single.php';

    public function __construct()
    {
        $this->apiKey = config('services.myemailverifier.key');
    }

    /**
     * Verifikasi email menggunakan MyEmailVerifier API.
     *
     * @param  string  $email
     * @return array{is_valid: bool, status: string, message: string, raw_response: array|null}
     */
    public function verify(string $email): array
    {
        try {
            $response = Http::timeout(10)->get($this->apiUrl, [
                'apikey' => $this->apiKey,
                'email'  => $email,
            ]);

            if (!$response->successful()) {
                Log::warning('MyEmailVerifier: HTTP error', [
                    'http_status' => $response->status(),
                    'email'       => $email,
                ]);

                return $this->fail('Gagal mengecek email. Silakan coba beberapa saat lagi.');
            }

            $data = $response->json();

            // Pastikan response memiliki key minimum yang dibutuhkan
            if (!isset($data['Status'])) {
                Log::warning('MyEmailVerifier: Response tidak lengkap', [
                    'email'    => $email,
                    'response' => $data,
                ]);

                return $this->fail('Gagal mengecek email. Silakan coba beberapa saat lagi.');
            }

            return $this->evaluate($data);

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('MyEmailVerifier: Connection timeout/error', [
                'email'   => $email,
                'message' => $e->getMessage(),
            ]);

            return $this->fail('Gagal mengecek email. Silakan coba beberapa saat lagi.');
        } catch (\Throwable $e) {
            Log::error('MyEmailVerifier: Unexpected error', [
                'email'   => $email,
                'message' => $e->getMessage(),
            ]);

            return $this->fail('Gagal mengecek email. Silakan coba beberapa saat lagi.');
        }
    }

    /**
     * Evaluasi response dari MyEmailVerifier berdasarkan aturan validasi.
     * Response kadang mengembalikan boolean sebagai string "true"/"false",
     * helper parseBool() menangani keduanya secara aman.
     */
    private function evaluate(array $data): array
    {
        $status     = $data['Status'] ?? '';
        $disposable = $this->parseBool($data['Disposable_Domain'] ?? false);
        $roleBased  = $this->parseBool($data['Role_Based'] ?? false);
        $diagnosis  = $data['Diagnosis'] ?? '';

        // 1. Tolak email disposable / temporary
        if ($disposable) {
            return $this->reject('Email sementara tidak diperbolehkan.', $data);
        }

        // 2. Tolak email role-based (admin@, info@, support@, dll)
        if ($roleBased) {
            return $this->reject(
                'Email role seperti admin, info, atau support tidak diperbolehkan. Gunakan email pribadi pelanggan.',
                $data
            );
        }

        // 3. Cek Status harus "Valid"
        if (strtolower($status) !== 'valid') {
            return $this->reject('Email tidak valid atau tidak aktif.', $data);
        }

        // 4. Semua kondisi terpenuhi — email valid
        return [
            'is_valid'     => true,
            'status'       => 'register',
            'message'      => 'Email valid dan terdaftar.',
            'raw_response' => $data,
        ];
    }

    /**
     * Parse nilai boolean dari string "true"/"false" maupun boolean asli.
     */
    private function parseBool(mixed $value): bool
    {
        if (is_bool($value)) {
            return $value;
        }

        return strtolower((string) $value) === 'true';
    }

    private function reject(string $message, array $data): array
    {
        return [
            'is_valid'     => false,
            'status'       => 'not_register',
            'message'      => $message,
            'raw_response' => $data,
        ];
    }

    private function fail(string $message): array
    {
        return [
            'is_valid'     => false,
            'status'       => 'not_register',
            'message'      => $message,
            'raw_response' => null,
        ];
    }
}
