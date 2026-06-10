<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class EmailReputationService
{
    protected string $apiKey;
    protected string $apiUrl = 'https://emailreputation.abstractapi.com/v1';

    public function __construct()
    {
        $this->apiKey = config('services.abstract_email_reputation.key');
    }

    /**
     * Verifikasi reputasi email menggunakan AbstractAPI Email Reputation API.
     *
     * @param  string  $email
     * @return array{is_valid: bool, status: string, message: string, raw_response: array|null}
     */
    public function verify(string $email): array
    {
        try {
            $response = Http::timeout(10)->get($this->apiUrl, [
                'api_key' => $this->apiKey,
                'email'   => $email,
            ]);

            if (!$response->successful()) {
                Log::warning('AbstractAPI EmailReputation: HTTP error', [
                    'http_status' => $response->status(),
                    'email'       => $email,
                ]);

                return $this->fail('Gagal mengecek email. Silakan coba beberapa saat lagi.');
            }

            $data = $response->json();

            return $this->evaluate($data);

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('AbstractAPI EmailReputation: Connection timeout/error', [
                'email'   => $email,
                'message' => $e->getMessage(),
            ]);

            return $this->fail('Gagal mengecek email. Silakan coba beberapa saat lagi.');
        } catch (\Throwable $e) {
            Log::error('AbstractAPI EmailReputation: Unexpected error', [
                'email'   => $email,
                'message' => $e->getMessage(),
            ]);

            return $this->fail('Gagal mengecek email. Silakan coba beberapa saat lagi.');
        }
    }

    /**
     * Evaluasi response dari AbstractAPI berdasarkan aturan validasi.
     */
    private function evaluate(array $data): array
    {
        $deliverability = $data['email_deliverability'] ?? [];
        $quality        = $data['email_quality'] ?? [];
        $risk           = $data['email_risk'] ?? [];

        // 1. Cek format email
        if (($deliverability['is_format_valid'] ?? false) !== true) {
            return $this->reject('Format email tidak valid.', $data);
        }

        // 2. Cek SMTP dan MX valid
        if (($deliverability['is_smtp_valid'] ?? false) !== true
            || ($deliverability['is_mx_valid'] ?? false) !== true
        ) {
            return $this->reject('Email tidak aktif atau tidak terdaftar.', $data);
        }

        // 3. Cek status deliverable
        if (($deliverability['status'] ?? '') !== 'deliverable'
            || ($deliverability['status_detail'] ?? '') !== 'valid_email'
        ) {
            return $this->reject('Email tidak aktif atau tidak terdaftar.', $data);
        }

        // 4. Tolak email disposable / temporary
        if (($quality['is_disposable'] ?? false) === true) {
            return $this->reject('Email sementara tidak diperbolehkan.', $data);
        }

        // 5. Tolak username mencurigakan
        if (($quality['is_username_suspicious'] ?? false) === true) {
            return $this->reject('Email terdeteksi mencurigakan. Silakan gunakan email lain.', $data);
        }

        // 6. Cek risiko alamat dan domain
        if (($risk['address_risk_status'] ?? '') !== 'low'
            || ($risk['domain_risk_status'] ?? '') !== 'low'
        ) {
            return $this->reject('Email terdeteksi berisiko. Silakan gunakan email lain.', $data);
        }

        // 7. Cek skor kualitas minimal 0.7
        $score = $quality['score'] ?? 0;
        if ((float) $score < 0.7) {
            return $this->reject('Kualitas email terlalu rendah. Silakan gunakan email aktif yang valid.', $data);
        }

        // Semua kondisi terpenuhi — email valid
        return [
            'is_valid'     => true,
            'status'       => 'register',
            'message'      => 'Email valid dan aktif.',
            'raw_response' => $data,
        ];
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
