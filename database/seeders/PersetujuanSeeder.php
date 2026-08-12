<?php

namespace Database\Seeders;

use App\Models\Organization;
use App\Models\Persetujuan;
use Illuminate\Database\Seeder;

class PersetujuanSeeder extends Seeder
{
    public function run(): void
    {
        $organization = Organization::first();
        $orgId = $organization ? $organization->id : 1;

        Persetujuan::create([
            'judul'           => 'Syarat dan Ketentuan Layanan Internet CIO Network',
            'konten'          => '<h2>1. Definisi</h2>
<p>Layanan Internet yang disediakan oleh CIO Network adalah layanan akses internet berbasis fiber optic dan/atau teknologi nirkabel dengan berbagai pilihan paket kecepatan.</p>

<h2>2. Hak dan Kewajiban Pelanggan</h2>
<p>2.1 Pelanggan wajib membayar biaya layanan sesuai paket yang dipilih setiap bulan.</p>
<p>2.2 Pelanggan dilarang menggunakan layanan untuk aktivitas ilegal termasuk namun tidak terbatas pada hacking, spamming, dan distribusi konten ilegal.</p>
<p>2.3 Pelanggan bertanggung jawab penuh atas keamanan perangkat yang terhubung ke jaringan CIO Network.</p>

<h2>3. Hak dan Kewajiban CIO Network</h2>
<p>3.1 CIO Network berhak melakukan pemeliharaan teknis yang dapat menyebabkan gangguan sementara dengan pemberitahuan sebelumnya.</p>
<p>3.2 CIO Network berhak menonaktifkan layanan apabila pelanggan melanggar ketentuan yang berlaku.</p>
<p>3.3 CIO Network akan berupaya memberikan layanan terbaik dengan ketersediaan jaringan sesuai SLA yang ditentukan.</p>

<h2>4. Pembayaran</h2>
<p>4.1 Pembayaran dilakukan di awal setiap bulan melalui metode pembayaran yang telah ditentukan.</p>
<p>4.2 Keterlambatan pembayaran akan dikenakan denda sesuai ketentuan yang berlaku.</p>
<p>4.3 Jika pembayaran tidak dilunasi dalam 30 hari, layanan akan dihentikan sementara.</p>

<h2>5. Masa Berlaku</h2>
<p>Ketentuan ini berlaku sejak pelanggan menandatangani dokumen persetujuan ini dan akan dievaluasi secara berkala oleh CIO Network.</p>

<p style="margin-top: 20px; color: #64748b; font-style: italic;">Dokumen ini telah disetujui secara digital oleh pelanggan melalui sistem pendaftaran online CIO Network.</p>',
            'is_active'       => true,
            'organization_id' => $orgId,
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);
    }
}
