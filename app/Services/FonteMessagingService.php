<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FonteMessagingService
{
    protected string $token;
    protected string $apiUrl = 'https://api.fonnte.com/send';

    public function __construct()
    {
        $this->token = config('services.fonnte.token');
    }

    /**
     * Normalisasi nomor telepon ke format 628xxx.
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

        return $phone;
    }

    /**
     * Kirim pesan WhatsApp ke nomor tujuan.
     */
    public function sendMessage(string $phone, string $message): bool
    {
        if (empty($phone) || empty($message)) {
            return false;
        }

        // Replace localhost with local IP or 127.0.0.1 so that links are clickable in WhatsApp
        if (str_contains($message, 'localhost')) {
            $localIp = gethostbyname(gethostname());
            if ($localIp && $localIp !== '127.0.0.1' && filter_var($localIp, FILTER_VALIDATE_IP)) {
                $message = str_replace('localhost', $localIp, $message);
            } else {
                $message = str_replace('localhost', '127.0.0.1', $message);
            }
        }

        $normalizedPhone = $this->normalize($phone);

        try {
            $response = Http::timeout(15)
                ->withHeaders([
                    'Authorization' => $this->token,
                ])
                ->asForm()
                ->post($this->apiUrl, [
                    'target' => $normalizedPhone,
                    'message' => $message,
                    'countryCode' => '62',
                ]);

            if ($response->successful()) {
                Log::info("FonteMessagingService: Sukses mengirim pesan ke {$normalizedPhone}.");
                return true;
            }

            Log::error('FonteMessagingService: Gagal mengirim pesan.', [
                'status' => $response->status(),
                'response' => $response->body()
            ]);
            return false;

        } catch (\Throwable $e) {
            Log::error('FonteMessagingService: Terjadi exception saat kirim pesan.', [
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Kirim beberapa pesan WhatsApp sekaligus.
     * 
     * @param array $messages Array dari ['phone' => '...', 'message' => '...']
     */
    public function sendMultipleMessages(array $messages): bool
    {
        if (empty($messages)) {
            return false;
        }

        $success = true;
        foreach ($messages as $msg) {
            if (empty($msg['phone']) || empty($msg['message'])) {
                continue;
            }

            $res = $this->sendMessage($msg['phone'], $msg['message']);
            if (!$res) {
                $success = false;
            }
        }

        return $success;
    }
}
