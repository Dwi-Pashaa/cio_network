<?php

namespace App\Services;

use App\Models\ProsedurSpam;
use Illuminate\Support\Facades\Log;

class ProsedurNotificationService
{
    protected NotificationDispatcher $dispatcher;

    public function __construct(NotificationDispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * Kirim notifikasi ke validator level berikutnya yang berstatus pending.
     */
    public function notifyNextPendingLevel(ProsedurSpam $spam)
    {
        // 1. Ambil checkpoint pending dengan level terendah
        $checkpoint = $spam->validations()
            ->where('status', 'pending')
            ->orderBy('level')
            ->first();

        if (!$checkpoint) {
            return;
        }

        // 2. Ambil template dari database berdasarkan level validator aktif
        $templateCode = "validator_level_" . $checkpoint->level;
        $templateRecord = \App\Models\ProsedurChatTemplate::where('code', $templateCode)->first();
        if (!$templateRecord) {
            Log::warning("ProsedurNotificationService: Template '{$templateCode}' tidak ditemukan di DB.");
            return;
        }

        $rawTemplate = $templateRecord->template;

        // 3. Cari user validator yang memiliki permission tersebut
        $validators = $this->getValidatorsForLevel($spam, $checkpoint->level);

        if ($validators->isEmpty()) {
            Log::info("ProsedurNotificationService: Tidak ada validator ditemukan untuk level {$checkpoint->level} pada request #{$spam->id}");
            return;
        }

        // 4. Load data pendukung pelanggan
        $customer = $spam->customer;
        $customer->loadMissing(['hometown', 'rt', 'rw', 'village', 'district', 'regencie', 'type', 'tipePelanggan']);
        $customerServiceType = $customer->type->name ?? $customer->tipePelanggan->name ?? 'Tidak diketahui';

        $addressParts = [];
        if (!empty($customer->hometown->name)) $addressParts[] = 'Kampung ' . $customer->hometown->name;
        if (!empty($customer->rt->name)) $addressParts[] = 'RT ' . $customer->rt->name;
        if (!empty($customer->rw->name)) $addressParts[] = 'RW ' . $customer->rw->name;
        if (!empty($customer->village->name)) $addressParts[] = 'Desa ' . $customer->village->name;
        if (!empty($customer->district->name)) $addressParts[] = 'Kec. ' . $customer->district->name;
        if (!empty($customer->regencie->name)) $addressParts[] = 'Kab. ' . $customer->regencie->name;
        $customerAddress = count($addressParts) > 0 ? implode(', ', $addressParts) : '-';

        $procedureTypeName = match ($spam->prosedur_type) {
            'onu-router' => 'Pergantian Perangkat ONU / Router',
            'pemutusan' => 'Pemutusan Layanan Pelanggan',
            'pergantian-layanan' => 'Pergantian Layanan Pelanggan',
            'pergantian-password' => 'Pergantian Password WiFi',
            default => $spam->prosedur_type
        };

        $details = $this->formatDetails($spam);
        $validationLink = route('spam.index');
        $subject = "Validasi Prosedur Baru (Level {$checkpoint->level}): {$procedureTypeName} - {$customer->name}";
        $title = "VALIDASI PROSEDUR LEVEL {$checkpoint->level}";

        $metadata = [
            'Tipe Prosedur'      => $procedureTypeName,
            'Diajukan Oleh'      => $spam->submittedBy->name ?? 'Teknisi',
            'Organisasi'         => $spam->organization->name ?? 'Internal',
            'Waktu Pengajuan'    => $spam->created_at->format('d-m-Y H:i:s'),
            'ID Pelanggan'       => strtoupper($customer->uuid ?? $customer->id),
            'Nama Pelanggan'     => $customer->name,
            'Alamat Pelanggan'   => $customerAddress,
            'Tipe Layanan'       => $customerServiceType,
        ];

        foreach ($validators as $validator) {
            $replacements = [
                '{validator_name}' => $validator->name,
                '{validation_level}' => $checkpoint->level,
                '{validator_label}' => $checkpoint->level_label,
                '{procedure_type}' => $procedureTypeName,
                '{technician_name}' => $spam->submittedBy->name ?? 'Teknisi',
                '{organization_name}' => $spam->organization->name ?? 'Internal',
                '{submission_date}' => $spam->created_at->format('d-m-Y H:i:s'),
                '{customer_id}' => strtoupper($customer->uuid ?? $customer->id),
                '{customer_name}' => $customer->name,
                '{customer_address}' => $customerAddress,
                '{service_type}' => $customerServiceType,
                '{customer_service_type}' => $customerServiceType,
                '{details}' => $details,
                '{validation_link}' => $validationLink,
            ];

            $message = str_replace(array_keys($replacements), array_values($replacements), $rawTemplate);

            $this->dispatcher->sendToUser(
                user: $validator,
                subject: $subject,
                title: $title,
                message: $message,
                actionUrl: $validationLink,
                actionText: 'Tinjau & Validasi Prosedur',
                metadata: $metadata,
                category: 'prosedur'
            );
        }
    }

    /**
     * Kirim notifikasi ke seluruh validator di level tertentu sekaligus.
     */
    public function notifyLevels(ProsedurSpam $spam, array $levels)
    {
        $levelsConfig = config('prosedur_levels.levels', []);

        // Load data pendukung pelanggan
        $customer = $spam->customer;
        $customer->loadMissing(['hometown', 'rt', 'rw', 'village', 'district', 'regencie', 'type', 'tipePelanggan']);
        $customerServiceType = $customer->type->name ?? $customer->tipePelanggan->name ?? 'Tidak diketahui';

        $addressParts = [];
        if (!empty($customer->hometown->name)) $addressParts[] = 'Kampung ' . $customer->hometown->name;
        if (!empty($customer->rt->name)) $addressParts[] = 'RT ' . $customer->rt->name;
        if (!empty($customer->rw->name)) $addressParts[] = 'RW ' . $customer->rw->name;
        if (!empty($customer->village->name)) $addressParts[] = 'Desa ' . $customer->village->name;
        if (!empty($customer->district->name)) $addressParts[] = 'Kec. ' . $customer->district->name;
        if (!empty($customer->regencie->name)) $addressParts[] = 'Kab. ' . $customer->regencie->name;
        $customerAddress = count($addressParts) > 0 ? implode(', ', $addressParts) : '-';

        $procedureTypeName = match ($spam->prosedur_type) {
            'onu-router' => 'Pergantian Perangkat ONU / Router',
            'pemutusan' => 'Pemutusan Layanan Pelanggan',
            'pergantian-layanan' => 'Pergantian Layanan Pelanggan',
            'pergantian-password' => 'Pergantian Password WiFi',
            default => $spam->prosedur_type
        };

        $details = $this->formatDetails($spam);
        $validationLink = route('spam.index');

        foreach ($levelsConfig as $level => $cfg) {
            if (!in_array($level, $levels)) {
                continue;
            }

            // Skip OLT (level 2) notification for service change procedures
            if ($spam->prosedur_type === 'pergantian-layanan' && $level === 2) {
                continue;
            }

            // Ambil template untuk level ini
            $templateCode = "validator_level_" . $level;
            $templateRecord = \App\Models\ProsedurChatTemplate::where('code', $templateCode)->first();
            if (!$templateRecord) {
                continue;
            }

            $rawTemplate = $templateRecord->template;
            $validators = $this->getValidatorsForLevel($spam, $level);
            $subject = "Validasi Prosedur Baru (Level {$level} - {$cfg['label']}): {$procedureTypeName} - {$customer->name}";
            $title = "VALIDASI PROSEDUR LEVEL {$level} ({$cfg['label']})";

            $metadata = [
                'Tipe Prosedur'      => $procedureTypeName,
                'Diajukan Oleh'      => $spam->submittedBy->name ?? 'Teknisi',
                'Organisasi'         => $spam->organization->name ?? 'Internal',
                'Waktu Pengajuan'    => $spam->created_at->format('d-m-Y H:i:s'),
                'ID Pelanggan'       => strtoupper($customer->uuid ?? $customer->id),
                'Nama Pelanggan'     => $customer->name,
                'Alamat Pelanggan'   => $customerAddress,
                'Tipe Layanan'       => $customerServiceType,
            ];

            foreach ($validators as $validator) {
                $replacements = [
                    '{validator_name}' => $validator->name,
                    '{validation_level}' => $level,
                    '{validator_label}' => $cfg['label'],
                    '{procedure_type}' => $procedureTypeName,
                    '{technician_name}' => $spam->submittedBy->name ?? 'Teknisi',
                    '{organization_name}' => $spam->organization->name ?? 'Internal',
                    '{submission_date}' => $spam->created_at->format('d-m-Y H:i:s'),
                    '{customer_id}' => strtoupper($customer->uuid ?? $customer->id),
                    '{customer_name}' => $customer->name,
                    '{customer_address}' => $customerAddress,
                    '{service_type}' => $customerServiceType,
                    '{customer_service_type}' => $customerServiceType,
                    '{details}' => $details,
                    '{validation_link}' => $validationLink,
                ];

                $message = str_replace(array_keys($replacements), array_values($replacements), $rawTemplate);

                $this->dispatcher->sendToUser(
                    user: $validator,
                    subject: $subject,
                    title: $title,
                    message: $message,
                    actionUrl: $validationLink,
                    actionText: 'Tinjau & Validasi Prosedur',
                    metadata: $metadata,
                    category: 'prosedur'
                );
            }
        }
    }

    /**
     * Kirim notifikasi ke seluruh validator di semua level (Level 1 s/d 4) sekaligus.
     */
    public function notifyAllLevels(ProsedurSpam $spam)
    {
        $this->notifyLevels($spam, [1, 2, 3, 4]);
    }

    /**
     * Kirim notifikasi ke teknisi bahwa pengajuan telah selesai disetujui & dieksekusi.
     */
    public function notifyTechnicianApproved(ProsedurSpam $spam)
    {
        $technician = $spam->submittedBy;
        if (!$technician) {
            return;
        }

        $templateRecord = \App\Models\ProsedurChatTemplate::where('code', 'technician_approved')->first();
        if (!$templateRecord) {
            Log::warning('ProsedurNotificationService: Template "technician_approved" tidak ditemukan.');
            return;
        }

        $rawTemplate = $templateRecord->template;
        $customer = $spam->customer;
        $customer->loadMissing(['type', 'tipePelanggan']);
        $customerServiceType = $customer->type->name ?? $customer->tipePelanggan->name ?? 'Tidak diketahui';

        $procedureTypeName = match ($spam->prosedur_type) {
            'onu-router' => 'Pergantian Perangkat ONU / Router',
            'pemutusan' => 'Pemutusan Layanan Pelanggan',
            'pergantian-layanan' => 'Pergantian Layanan Pelanggan',
            'pergantian-password' => 'Pergantian Password WiFi',
            default => $spam->prosedur_type
        };

        $replacements = [
            '{technician_name}' => $technician->name,
            '{procedure_type}' => $procedureTypeName,
            '{customer_id}' => strtoupper($customer->uuid ?? $customer->id),
            '{customer_name}' => $customer->name,
            '{service_type}' => $customerServiceType,
            '{customer_service_type}' => $customerServiceType,
            '{execution_date}' => $spam->executed_at ? $spam->executed_at->format('d-m-Y H:i:s') : now()->format('d-m-Y H:i:s'),
        ];

        $message = str_replace(array_keys($replacements), array_values($replacements), $rawTemplate);
        $subject = "Pengajuan Prosedur Disetujui: {$procedureTypeName} - {$customer->name}";

        $this->dispatcher->sendToUser(
            user: $technician,
            subject: $subject,
            title: 'PROSEDUR SELESAI DIEKSEKUSI',
            message: $message,
            actionUrl: route('spam.index'),
            actionText: 'Lihat Status Prosedur',
            metadata: [
                'Tipe Prosedur'   => $procedureTypeName,
                'Nama Pelanggan'  => $customer->name,
                'ID Pelanggan'    => strtoupper($customer->uuid ?? $customer->id),
                'Status'          => 'Disetujui & Diterapkan',
                'Waktu Eksekusi'  => $spam->executed_at ? $spam->executed_at->format('d-m-Y H:i:s') : now()->format('d-m-Y H:i:s'),
            ],
            category: 'prosedur'
        );
    }

    /**
     * Kirim notifikasi ke teknisi bahwa pengajuan ditolak oleh salah satu validator.
     */
    public function notifyTechnicianRejected(ProsedurSpam $spam, string $rejectorName, string $reason)
    {
        $technician = $spam->submittedBy;
        if (!$technician) {
            return;
        }

        $templateRecord = \App\Models\ProsedurChatTemplate::where('code', 'technician_rejected')->first();
        if (!$templateRecord) {
            Log::warning('ProsedurNotificationService: Template "technician_rejected" tidak ditemukan.');
            return;
        }

        $rawTemplate = $templateRecord->template;
        $customer = $spam->customer;
        $customer->loadMissing(['type', 'tipePelanggan']);
        $customerServiceType = $customer->type->name ?? $customer->tipePelanggan->name ?? 'Tidak diketahui';

        $procedureTypeName = match ($spam->prosedur_type) {
            'onu-router' => 'Pergantian Perangkat ONU / Router',
            'pemutusan' => 'Pemutusan Layanan Pelanggan',
            'pergantian-layanan' => 'Pergantian Layanan Pelanggan',
            'pergantian-password' => 'Pergantian Password WiFi',
            default => $spam->prosedur_type
        };

        $checkpoint = $spam->validations()->where('status', 'rejected')->first();
        $levelNum = $checkpoint ? $checkpoint->level : '-';

        $replacements = [
            '{technician_name}' => $technician->name,
            '{procedure_type}' => $procedureTypeName,
            '{customer_id}' => strtoupper($customer->uuid ?? $customer->id),
            '{customer_name}' => $customer->name,
            '{service_type}' => $customerServiceType,
            '{customer_service_type}' => $customerServiceType,
            '{validator_name}' => $rejectorName,
            '{validation_level}' => $levelNum,
            '{validation_notes}' => $reason,
        ];

        $message = str_replace(array_keys($replacements), array_values($replacements), $rawTemplate);
        $subject = "Pengajuan Prosedur Ditolak: {$procedureTypeName} - {$customer->name}";

        $this->dispatcher->sendToUser(
            user: $technician,
            subject: $subject,
            title: 'PROSEDUR DITOLAK',
            message: $message,
            actionUrl: route('spam.index'),
            actionText: 'Tinjau Pengajuan Prosedur',
            metadata: [
                'Tipe Prosedur'     => $procedureTypeName,
                'Nama Pelanggan'    => $customer->name,
                'ID Pelanggan'      => strtoupper($customer->uuid ?? $customer->id),
                'Ditolak Oleh'      => "{$rejectorName} (Level {$levelNum})",
                'Alasan Penolakan'  => $reason,
            ],
            category: 'prosedur'
        );
    }

    /**
     * Ambil daftar validator yang memiliki hak validasi pada level tertentu,
     * serta mencocokkan filter organisasi.
     */
    private function getValidatorsForLevel(ProsedurSpam $spam, int $level): \Illuminate\Support\Collection
    {
        $levelsConfig = config('prosedur_levels.levels', []);
        $levelCfg = $levelsConfig[$level] ?? null;

        if (!$levelCfg) {
            return collect();
        }

        $permission = $levelCfg['permission'];

        return \App\Models\User::permission($permission)
            ->where(function ($query) use ($spam) {
                // Validator harus dalam satu organisasi yang sama dengan pengaju,
                // atau organisasi validator bertipe 'internal' (superadmin/global).
                $query->where('organization_id', $spam->organization_id)
                    ->orWhereHas('organization', function ($q) {
                        $q->where('type', 'internal');
                    });
            })
            ->get();
    }

    /**
     * Format data perubahan spesifik untuk template {details}.
     */
    private function formatDetails(ProsedurSpam $spam): string
    {
        $payload = $spam->payload ?? [];

        switch ($spam->prosedur_type) {
            case 'onu-router':
                $oldMac = $payload['mac_address_old'] ?? '-';
                $newMac = $payload['mac_address_new'] ?? '-';
                $oldRouter = $payload['router_lama_name'] ?? '-';
                $newRouter = $payload['router_new_name'] ?? '-';
                return "- MAC Address Lama: {$oldMac}\n"
                    . "- MAC Address Baru: {$newMac}\n"
                    . "- Router Lama: {$oldRouter}\n"
                    . "- Router Baru: {$newRouter}";

            case 'pemutusan':
                $alasan = $payload['alasan'] ?? '-';
                $details = "- Alasan Pemutusan: {$alasan}";
                if (!empty($payload['foto_perangkat_path'])) {
                    $path = $payload['foto_perangkat_path'];
                    $url = file_exists(public_path($path)) ? asset($path) : asset('storage/' . $path);
                    $details .= "\n- Bukti Perangkat: " . $url;
                }
                if (!empty($payload['foto_pembayaran_path'])) {
                    $path = $payload['foto_pembayaran_path'];
                    $url = file_exists(public_path($path)) ? asset($path) : asset('storage/' . $path);
                    $details .= "\n- Bukti Pembayaran: " . $url;
                }
                return $details;

            case 'pergantian-layanan':
                $serviceType = $payload['service_type'] ?? '';
                if ($serviceType === 'voucher-ke-pppoe') {
                    $paket = $payload['paket_name'] ?? '-';
                    $price = $payload['price_name'] ?? '-';
                    $wifiName = $payload['name_wifi'] ?? '-';
                    $wifiPass = $payload['password_wifi'] ?? '-';
                    $pppoeUser = $payload['pppoe_username'] ?? '-';
                    $pppoePass = $payload['pppoe_password'] ?? '-';
                    return "- Tipe Transisi: Voucher ke PPPoE\n"
                        . "- Paket Baru: {$paket}\n"
                        . "- Pembayaran Baru: {$price}\n"
                        . "- Nama WiFi: {$wifiName}\n"
                        . "- Password WiFi: {$wifiPass}\n"
                        . "- Username PPPoE: {$pppoeUser}\n"
                        . "- Password PPPoE: {$pppoePass}";
                } elseif ($serviceType === 'pppoe-ke-voucher') {
                    return "- Tipe Transisi: PPPoE ke Voucher\n"
                        . "- (Semua kredensial PPPoE dan WiFi lama akan dinonaktifkan)";
                }
                return "- Tipe Transisi: tidak diketahui";

            case 'pergantian-password':
                $wifiName = $payload['name_wifi'] ?? '';
                $wifiPass = $payload['password_wifi'] ?? '-';
                $wifiNameStr = !empty($wifiName) ? $wifiName : '(Tidak diubah / Tetap)';
                return "- Nama WiFi Baru: {$wifiNameStr}\n"
                    . "- Password WiFi Baru: {$wifiPass}";

            default:
                return '-';
        }
    }

    /**
     * Kirim notifikasi ke WhatsApp & Email pelanggan bahwa password WiFi telah berhasil diubah.
     */
    public function notifyCustomerPasswordChanged(ProsedurSpam $spam)
    {
        $customer = $spam->customer;
        if (!$customer) {
            return;
        }

        $payload = $spam->payload ?? [];
        $wifiName = $payload['name_wifi'] ?? $customer->name_wifi ?? '-';
        $wifiPass = $payload['password_wifi'] ?? '-';

        $message = "*✅ PEMBERITAHUAN PERUBAHAN PASSWORD*\n\n"
            . "Halo *{$customer->name}*,\n"
            . "Kami ingin menginformasikan bahwa permintaan perubahan password WiFi Anda telah berhasil divalidasi dan diperbarui oleh tim ONC.\n\n"
            . "*Detail Perubahan:* \n"
            . "- Nama WiFi: *{$wifiName}*\n"
            . "- Password WiFi Baru: *{$wifiPass}*\n\n"
            . "Jika Anda tidak merasa melakukan perubahan ini, silakan hubungi tim Customer Support kami segera.\n\n"
            . "Terima kasih,\n"
            . "*CIO Network*";

        $this->dispatcher->sendToRecipient(
            phone: $customer->telp,
            email: $customer->email,
            subject: 'Pemberitahuan Perubahan Password WiFi - CIO Network',
            title: 'PERUBAHAN PASSWORD WIFI BERHASIL',
            message: $message,
            actionUrl: null,
            actionText: null,
            metadata: [
                'Nama Pelanggan'  => $customer->name,
                'ID Pelanggan'    => strtoupper($customer->uuid ?? $customer->id),
                'Nama WiFi'       => $wifiName,
                'Password Baru'   => $wifiPass,
                'Status'          => 'Aktif Diperbarui',
            ],
            channel: 'both',
            recipientName: $customer->name
        );
    }
}
