<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\NotificationSetting;
use App\Services\NotificationDispatcher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationSettingController extends Controller
{
    /**
     * Tampilkan halaman pengaturan notifikasi.
     */
    public function index()
    {
        $user = Auth::user();
        
        $setting = NotificationSetting::firstOrCreate(
            ['user_id' => $user->id],
            [
                'organization_id'     => $user->organization_id,
                'channel'             => 'both',
                'notify_prosedur'     => true,
                'notify_troubleshoot' => true,
                'notify_pendaftaran'  => true,
                'notify_complain'     => true,
            ]
        );

        $fonnteTokenConfigured = !empty(config('services.fonnte.token'));
        $mailHostConfigured = !empty(config('mail.mailers.smtp.host'));

        return view('pages.notification-setting.index', compact('user', 'setting', 'fonnteTokenConfigured', 'mailHostConfigured'));
    }

    /**
     * Simpan perubahan preferensi notifikasi.
     */
    public function update(Request $request)
    {
        $request->validate([
            'channel'             => 'required|in:whatsapp,email,both,none',
            'notify_prosedur'     => 'nullable|boolean',
            'notify_troubleshoot' => 'nullable|boolean',
            'notify_pendaftaran'  => 'nullable|boolean',
            'notify_complain'     => 'nullable|boolean',
            'telp'                => 'nullable|string|max:25',
            'email'               => 'nullable|email|max:100',
        ]);

        $user = Auth::user();

        // Update kontak jika diisi
        $userUpdates = [];
        if ($request->has('telp')) {
            $userUpdates['telp'] = $request->telp;
        }
        if ($request->filled('email') && $request->email !== $user->email) {
            $request->validate([
                'email' => 'unique:users,email,' . $user->id,
            ]);
            $userUpdates['email'] = $request->email;
        }
        if (!empty($userUpdates)) {
            $user->update($userUpdates);
        }

        // Update atau buat notification setting
        NotificationSetting::updateOrCreate(
            ['user_id' => $user->id],
            [
                'organization_id'     => $user->organization_id,
                'channel'             => $request->channel,
                'notify_prosedur'     => $request->boolean('notify_prosedur'),
                'notify_troubleshoot' => $request->boolean('notify_troubleshoot'),
                'notify_pendaftaran'  => $request->boolean('notify_pendaftaran'),
                'notify_complain'     => $request->boolean('notify_complain'),
            ]
        );

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status'  => 'success',
                'message' => 'Pengaturan notifikasi berhasil disimpan.',
            ]);
        }

        return redirect()->back()->with('success', 'Pengaturan notifikasi berhasil disimpan.');
    }

    /**
     * Kirim notifikasi uji coba ke pengguna aktif.
     */
    public function testSend(Request $request)
    {
        $user = Auth::user();
        $setting = $user->notificationSetting;
        $channel = $setting->channel ?? 'both';

        if ($channel === 'none') {
            return response()->json([
                'status'  => 'error',
                'message' => 'Saluran notifikasi Anda sedang dinonaktifkan (None). Silakan pilih WhatsApp, Email, atau Keduanya.',
            ], 422);
        }

        $now = now()->format('d-m-Y H:i:s');
        $subject = "Uji Coba Notifikasi - CIO Network";
        $title = "NOTIFIKASI UJI COBA";
        $message = "Halo *{$user->name}*,\n\n"
            . "Ini adalah pesan uji coba sistem notifikasi dari *CIO Network*.\n\n"
            . "- *Saluran Aktif:* " . strtoupper($channel) . "\n"
            . "- *No. WhatsApp:* " . ($user->telp ?: 'Belum diatur') . "\n"
            . "- *Email Tujuan:* " . ($user->email ?: 'Belum diatur') . "\n"
            . "- *Waktu Pengujian:* {$now}\n\n"
            . "Jika Anda menerima pesan ini, integrasi notifikasi Anda telah berfungsi dengan baik! ✅";

        $metadata = [
            'Nama Pengguna'   => $user->name,
            'Saluran Dipilih' => strtoupper($channel),
            'No. WhatsApp'    => $user->telp ?: '-',
            'Email'           => $user->email ?: '-',
            'Waktu Uji Coba'  => $now,
            'Status'          => 'Berhasil Terkirim',
        ];

        $dispatcher = app(NotificationDispatcher::class);
        $result = $dispatcher->sendToUser(
            user: $user,
            subject: $subject,
            title: $title,
            message: $message,
            actionUrl: route('notification.setting.index'),
            actionText: 'Buka Pengaturan Notifikasi',
            metadata: $metadata,
            category: 'general'
        );

        $sentChannels = [];
        if ($result['whatsapp']) $sentChannels[] = 'WhatsApp (Fonnte)';
        if ($result['email']) $sentChannels[] = 'Email';

        if (empty($sentChannels)) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Gagal mengirim notifikasi uji coba. Pastikan No. WhatsApp / Email sudah terisi dengan benar dan konfigurasi server aktif.',
                'result'  => $result,
            ], 500);
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'Notifikasi uji coba berhasil dikirim melalui: ' . implode(' & ', $sentChannels),
            'result'  => $result,
        ]);
    }
}
