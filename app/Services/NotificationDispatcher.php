<?php

namespace App\Services;

use App\Mail\GenericNotificationMail;
use App\Models\NotificationSetting;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class NotificationDispatcher
{
    protected FonteMessagingService $fonteService;

    public function __construct(FonteMessagingService $fonteService)
    {
        $this->fonteService = $fonteService;
    }

    /**
     * Kirim notifikasi ke satu User sistem sesuai preferensi channel-nya (WA / Email / Both).
     *
     * @param User $user
     * @param string $subject
     * @param string $title
     * @param string $message
     * @param string|null $actionUrl
     * @param string|null $actionText
     * @param array $metadata
     * @param string $category 'prosedur' | 'troubleshoot' | 'pendaftaran' | 'complain' | 'general'
     * @return array Status pengiriman ['whatsapp' => bool, 'email' => bool]
     */
    public function sendToUser(
        User $user,
        string $subject,
        string $title,
        string $message,
        ?string $actionUrl = null,
        ?string $actionText = null,
        array $metadata = [],
        string $category = 'general'
    ): array {
        $setting = $user->notificationSetting;
        $channel = $setting->channel ?? 'both';

        // Cek filter kategori jika ada setting tersimpan
        if ($setting) {
            if ($category === 'prosedur' && !$setting->notify_prosedur) {
                return ['whatsapp' => false, 'email' => false, 'skipped' => 'category_disabled'];
            }
            if ($category === 'troubleshoot' && !$setting->notify_troubleshoot) {
                return ['whatsapp' => false, 'email' => false, 'skipped' => 'category_disabled'];
            }
            if ($category === 'pendaftaran' && !$setting->notify_pendaftaran) {
                return ['whatsapp' => false, 'email' => false, 'skipped' => 'category_disabled'];
            }
            if ($category === 'complain' && !$setting->notify_complain) {
                return ['whatsapp' => false, 'email' => false, 'skipped' => 'category_disabled'];
            }
        }

        return $this->dispatch(
            phone: $user->telp,
            email: $user->email,
            channel: $channel,
            subject: $subject,
            title: $title,
            message: $message,
            actionUrl: $actionUrl,
            actionText: $actionText,
            metadata: $metadata,
            recipientName: $user->name
        );
    }

    /**
     * Kirim notifikasi ke sekumpulan User.
     *
     * @param iterable $users
     * @param string $subject
     * @param string $title
     * @param string $message
     * @param string|null $actionUrl
     * @param string|null $actionText
     * @param array $metadata
     * @param string $category
     * @return void
     */
    public function sendToMultipleUsers(
        iterable $users,
        string $subject,
        string $title,
        string $message,
        ?string $actionUrl = null,
        ?string $actionText = null,
        array $metadata = [],
        string $category = 'general'
    ): void {
        foreach ($users as $user) {
            if ($user instanceof User) {
                $this->sendToUser($user, $subject, $title, $message, $actionUrl, $actionText, $metadata, $category);
            }
        }
    }

    /**
     * Kirim notifikasi ke penerima langsung (Pelanggan / Calon Pelanggan / Nomor & Email Spesifik).
     *
     * @param string|null $phone
     * @param string|null $email
     * @param string $subject
     * @param string $title
     * @param string $message
     * @param string|null $actionUrl
     * @param string|null $actionText
     * @param array $metadata
     * @param string $channel 'whatsapp' | 'email' | 'both' | 'none'
     * @param string|null $recipientName
     * @return array
     */
    public function sendToRecipient(
        ?string $phone,
        ?string $email,
        string $subject,
        string $title,
        string $message,
        ?string $actionUrl = null,
        ?string $actionText = null,
        array $metadata = [],
        string $channel = 'both',
        ?string $recipientName = null
    ): array {
        return $this->dispatch(
            phone: $phone,
            email: $email,
            channel: $channel,
            subject: $subject,
            title: $title,
            message: $message,
            actionUrl: $actionUrl,
            actionText: $actionText,
            metadata: $metadata,
            recipientName: $recipientName
        );
    }

    /**
     * Eksekusi pengiriman riil ke WhatsApp Fonnte dan/atau Email Laravel Mailer.
     */
    protected function dispatch(
        ?string $phone,
        ?string $email,
        string $channel,
        string $subject,
        string $title,
        string $message,
        ?string $actionUrl = null,
        ?string $actionText = null,
        array $metadata = [],
        ?string $recipientName = null
    ): array {
        $result = [
            'whatsapp' => false,
            'email'    => false,
        ];

        if ($channel === 'none') {
            return $result;
        }

        // 1. Eksekusi WhatsApp jika channel mencakup WA
        if (in_array($channel, ['whatsapp', 'both']) && !empty($phone)) {
            try {
                // Buat pesan lengkap untuk WhatsApp
                $waMessage = $message;
                if (!empty($actionUrl) && !str_contains($waMessage, $actionUrl)) {
                    $waMessage .= "\n\n🔗 *Link Detail:* " . $actionUrl;
                }

                $res = $this->fonteService->sendMessage($phone, $waMessage);
                $result['whatsapp'] = $res;
            } catch (\Throwable $e) {
                Log::error('NotificationDispatcher (WA Error): ' . $e->getMessage(), [
                    'phone' => $phone,
                    'subject' => $subject,
                ]);
            }
        }

        // 2. Eksekusi Email jika channel mencakup Email
        if (in_array($channel, ['email', 'both']) && !empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
            try {
                // Bersihkan marker bold WhatsApp (*) untuk format email yang lebih rapi jika diinginkan
                $cleanMessage = str_replace(['*', '_'], '', $message);

                Mail::to($email, $recipientName)->send(
                    new GenericNotificationMail(
                        subject: $subject,
                        title: $title,
                        messageBody: $cleanMessage,
                        actionUrl: $actionUrl,
                        actionText: $actionText,
                        metaDetails: $metadata
                    )
                );
                $result['email'] = true;
                Log::info("NotificationDispatcher: Sukses mengirim email notifikasi ke {$email}.");
            } catch (\Throwable $e) {
                Log::error('NotificationDispatcher (Email Error): ' . $e->getMessage(), [
                    'email' => $email,
                    'subject' => $subject,
                ]);
            }
        }

        return $result;
    }
}
