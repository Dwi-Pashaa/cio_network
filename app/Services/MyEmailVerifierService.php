<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class MyEmailVerifierService
{
    /**
     * Daftar domain email sementara (disposable) yang dilarang.
     */
    protected array $disposableDomains = [
        'mailinator.com',
        'tempmail.com',
        '10minutemail.com',
        'guerrillamail.com',
        'trashmail.com',
        'sharklasers.com',
        'dispostable.com',
        'yopmail.com',
        'fakeinbox.com',
        'getairmail.com',
        'throwawaymail.com',
    ];

    /**
     * Verifikasi email menggunakan validasi format dan DNS MX record native PHP.
     * Cepat, gratis selamanya, tanpa kuota/API key pihak ketiga.
     *
     * @param  string  $email
     * @return array{is_valid: bool, status: string, message: string, raw_response: array|null}
     */
    public function verify(string $email): array
    {
        $email = trim($email);

        // 1. Validasi format email dasar
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->reject('Format email tidak valid.');
        }

        // Regex ekstra untuk memastikan karakter dan struktur email standar
        if (!preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $email)) {
            return $this->reject('Format email tidak valid.');
        }

        // 2. Ambil domain dari email
        $domain = substr(strrchr($email, "@"), 1);
        if (!$domain) {
            return $this->reject('Domain email tidak valid.');
        }

        $domain = strtolower(trim($domain));

        // 3. Tolak jika menggunakan email disposable / temporary
        if (in_array($domain, $this->disposableDomains, true)) {
            return $this->reject('Email sementara (disposable) tidak diperbolehkan.');
        }

        // 4. Verifikasi Mail Server aktif melalui DNS MX Record (atau fallback A record)
        try {
            $hasMx = checkdnsrr($domain, 'MX');
            $hasA  = $hasMx ? true : checkdnsrr($domain, 'A');

            if (!$hasMx && !$hasA) {
                return $this->reject("Domain email '{$domain}' tidak aktif atau tidak dapat menerima email.");
            }
        } catch (\Throwable $e) {
            Log::warning('Native DNS MX check error: ' . $e->getMessage(), ['domain' => $domain]);
            // Jika terjadi error pada fungsi DNS (misal DNS server timeout), tetap loloskan jika format valid
        }

        return [
            'is_valid'     => true,
            'status'       => 'register',
            'message'      => 'Email valid dan siap digunakan.',
            'raw_response' => [
                'email'  => $email,
                'domain' => $domain,
                'method' => 'native_dns_mx',
            ],
        ];
    }

    private function reject(string $message): array
    {
        return [
            'is_valid'     => false,
            'status'       => 'not_register',
            'message'      => $message,
            'raw_response' => null,
        ];
    }
}

