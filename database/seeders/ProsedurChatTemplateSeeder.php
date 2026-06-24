<?php

namespace Database\Seeders;

use App\Models\ProsedurChatTemplate;
use Illuminate\Database\Seeder;

class ProsedurChatTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus template lama jika ada
        ProsedurChatTemplate::where('code', 'validator_notification')->delete();

        $templates = [
            [
                'code' => 'validator_level_1',
                'name' => 'Notifikasi Validasi - Level 1 (Admin)',
                'template' => "*🔔 VALIDASI PROSEDUR BARU (LEVEL 1)*\n\nHalo *{validator_name}*,\nAda pengajuan prosedur baru yang membutuhkan validasi Anda selaku Admin:\n\n📌 *Prosedur:* {procedure_type}\n👤 *Diajukan Oleh:* {technician_name}\n🏢 *Organisasi:* {organization_name}\n📅 *Tanggal:* {submission_date}\n\n*Detail Pelanggan:*\n- ID Pelanggan: {customer_id}\n- Nama Pelanggan: {customer_name}\n- Alamat: {customer_address}\n\n*Detail Pengajuan:*\n{details}\n\nSilakan tinjau dan lakukan tindakan pada link berikut:\n🔗 {validation_link}",
                'description' => "Dikirim ke validator Level 1 (Admin) untuk meminta validasi.\nVariabel yang didukung:\n- {validator_name} (Nama validator)\n- {validation_level} (Level validasi: 1)\n- {validator_label} (Label level validator: Admin)\n- {procedure_type} (Tipe prosedur: Pergantian Perangkat/Pemutusan Layanan/Pergantian Layanan)\n- {technician_name} (Nama teknisi pengaju)\n- {organization_name} (Nama organisasi pengaju)\n- {submission_date} (Tanggal & waktu pengajuan)\n- {customer_id} (ID Pelanggan)\n- {customer_name} (Nama pelanggan)\n- {customer_address} (Alamat pelanggan)\n- {details} (Detail perubahan spesifik sesuai tipe prosedur)\n- {validation_link} (Link ke halaman validasi)"
            ],
            [
                'code' => 'validator_level_2',
                'name' => 'Notifikasi Validasi - Level 2 (OLT)',
                'template' => "*🔔 VALIDASI PROSEDUR BARU (LEVEL 2)*\n\nHalo *{validator_name}*,\nAda pengajuan prosedur baru yang membutuhkan validasi Anda selaku OLT:\n\n📌 *Prosedur:* {procedure_type}\n👤 *Diajukan Oleh:* {technician_name}\n🏢 *Organisasi:* {organization_name}\n📅 *Tanggal:* {submission_date}\n\n*Detail Pelanggan:*\n- ID Pelanggan: {customer_id}\n- Nama Pelanggan: {customer_name}\n- Alamat: {customer_address}\n\n*Detail Pengajuan:*\n{details}\n\nSilakan tinjau dan lakukan tindakan pada link berikut:\n🔗 {validation_link}",
                'description' => "Dikirim ke validator Level 2 (OLT) untuk meminta validasi.\nVariabel yang didukung:\n- {validator_name} (Nama validator)\n- {validation_level} (Level validasi: 2)\n- {validator_label} (Label level validator: OLT)\n- {procedure_type} (Tipe prosedur: Pergantian Perangkat/Pemutusan Layanan/Pergantian Layanan)\n- {technician_name} (Nama teknisi pengaju)\n- {organization_name} (Nama organisasi pengaju)\n- {submission_date} (Tanggal & waktu pengajuan)\n- {customer_id} (ID Pelanggan)\n- {customer_name} (Nama pelanggan)\n- {customer_address} (Alamat pelanggan)\n- {details} (Detail perubahan spesifik sesuai tipe prosedur)\n- {validation_link} (Link ke halaman validasi)"
            ],
            [
                'code' => 'validator_level_3',
                'name' => 'Notifikasi Validasi - Level 3 (Mix Radius)',
                'template' => "*🔔 VALIDASI PROSEDUR BARU (LEVEL 3)*\n\nHalo *{validator_name}*,\nAda pengajuan prosedur baru yang membutuhkan validasi Anda selaku Mix Radius:\n\n📌 *Prosedur:* {procedure_type}\n👤 *Diajukan Oleh:* {technician_name}\n🏢 *Organisasi:* {organization_name}\n📅 *Tanggal:* {submission_date}\n\n*Detail Pelanggan:*\n- ID Pelanggan: {customer_id}\n- Nama Pelanggan: {customer_name}\n- Alamat: {customer_address}\n\n*Detail Pengajuan:*\n{details}\n\nSilakan tinjau dan lakukan tindakan pada link berikut:\n🔗 {validation_link}",
                'description' => "Dikirim ke validator Level 3 (Mix Radius) untuk meminta validasi.\nVariabel yang didukung:\n- {validator_name} (Nama validator)\n- {validation_level} (Level validasi: 3)\n- {validator_label} (Label level validator: Mix Radius)\n- {procedure_type} (Tipe prosedur: Pergantian Perangkat/Pemutusan Layanan/Pergantian Layanan)\n- {technician_name} (Nama teknisi pengaju)\n- {organization_name} (Nama organisasi pengaju)\n- {submission_date} (Tanggal & waktu pengajuan)\n- {customer_id} (ID Pelanggan)\n- {customer_name} (Nama pelanggan)\n- {customer_address} (Alamat pelanggan)\n- {details} (Detail perubahan spesifik sesuai tipe prosedur)\n- {validation_link} (Link ke halaman validasi)"
            ],
            [
                'code' => 'validator_level_4',
                'name' => 'Notifikasi Validasi - Level 4 (ONC)',
                'template' => "*🔔 VALIDASI PROSEDUR BARU (LEVEL 4)*\n\nHalo *{validator_name}*,\nAda pengajuan prosedur baru yang membutuhkan validasi Anda selaku ONC:\n\n📌 *Prosedur:* {procedure_type}\n👤 *Diajukan Oleh:* {technician_name}\n🏢 *Organisasi:* {organization_name}\n📅 *Tanggal:* {submission_date}\n\n*Detail Pelanggan:*\n- ID Pelanggan: {customer_id}\n- Nama Pelanggan: {customer_name}\n- Alamat: {customer_address}\n\n*Detail Pengajuan:*\n{details}\n\nSilakan tinjau dan lakukan tindakan pada link berikut:\n🔗 {validation_link}",
                'description' => "Dikirim ke validator Level 4 (ONC) untuk meminta validasi.\nVariabel yang didukung:\n- {validator_name} (Nama validator)\n- {validation_level} (Level validasi: 4)\n- {validator_label} (Label level validator: ONC)\n- {procedure_type} (Tipe prosedur: Pergantian Perangkat/Pemutusan Layanan/Pergantian Layanan)\n- {technician_name} (Nama teknisi pengaju)\n- {organization_name} (Nama organisasi pengaju)\n- {submission_date} (Tanggal & waktu pengajuan)\n- {customer_id} (ID Pelanggan)\n- {customer_name} (Nama pelanggan)\n- {customer_address} (Alamat pelanggan)\n- {details} (Detail perubahan spesifik sesuai tipe prosedur)\n- {validation_link} (Link ke halaman validasi)"
            ],
            [
                'code' => 'technician_approved',
                'name' => 'Pengajuan Disetujui (Untuk Teknisi)',
                'template' => "*✅ PROSEDUR SELESAI DIEKSEKUSI*\n\nHalo *{technician_name}*,\nPengajuan prosedur Anda telah disetujui oleh seluruh validator dan berhasil diterapkan ke sistem:\n\n📌 *Prosedur:* {procedure_type}\n👤 *Pelanggan:* {customer_id} - {customer_name}\n📅 *Waktu Eksekusi:* {execution_date}\n\nPerubahan data telah aktif di router/sistem. Terima kasih atas kerja samanya!",
                'description' => "Dikirim ke teknisi ketika pengajuan telah disetujui oleh semua level validator.\nVariabel yang didukung:\n- {technician_name} (Nama teknisi pengaju)\n- {procedure_type} (Tipe prosedur)\n- {customer_id} (ID Pelanggan)\n- {customer_name} (Nama pelanggan)\n- {execution_date} (Tanggal & waktu eksekusi)"
            ],
            [
                'code' => 'technician_rejected',
                'name' => 'Pengajuan Ditolak (Untuk Teknisi)',
                'template' => "*❌ PROSEDUR DITOLAK*\n\nHalo *{technician_name}*,\nMohon maaf, pengajuan prosedur Anda ditolak oleh validator:\n\n📌 *Prosedur:* {procedure_type}\n👤 *Pelanggan:* {customer_id} - {customer_name}\n🚫 *Ditolak Oleh:* {validator_name} (Level {validation_level})\n💬 *Alasan Penolakan:* \"{validation_notes}\"\n\nSilakan tinjau kembali data pengajuan Anda dan ajukan ulang jika diperlukan.",
                'description' => "Dikirim ke teknisi ketika salah satu validator menolak pengajuan.\nVariabel yang didukung:\n- {technician_name} (Nama teknisi pengaju)\n- {procedure_type} (Tipe prosedur)\n- {customer_id} (ID Pelanggan)\n- {customer_name} (Nama pelanggan)\n- {validator_name} (Nama validator yang menolak)\n- {validation_level} (Level validator yang menolak)\n- {validation_notes} (Alasan penolakan)"
            ]
        ];

        foreach ($templates as $data) {
            ProsedurChatTemplate::updateOrCreate(
                ['code' => $data['code']],
                [
                    'name' => $data['name'],
                    'template' => $data['template'],
                    'description' => $data['description']
                ]
            );
        }
    }
}
