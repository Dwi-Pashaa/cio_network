<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // 'lihat tipe pelanggan',
            // 'tambah tipe pelanggan',
            // 'edit tipe pelanggan',
            // 'hapus tipe pelanggan',
            // 'lihat tipe layanan',
            // 'tambah tipe layanan',
            // 'edit tipe layanan',
            // 'hapus tipe layanan',
            // 'lihat level',
            // 'tambah level',
            // 'edit level',
            // 'hapus level',
            // 'lihat user',
            // 'tambah user',
            // 'edit user',
            // 'hapus user',
            // 'lihat organisasi',
            // 'tambah organisasi',
            // 'edit organisasi',
            // 'hapus organisasi',
            // 'verifikasi email',
            // 'verifikasi whatsapp',
            // 'download qrcode',
            // 'tambah server',
            // 'edit server',
            // 'hapus server',
            // 'lihat server'
            // 'pergantian perangkat',
            // 'pemutusan layanan',
            // 'pergantian layanan',

            // // ── VALIDASI PROSEDUR (dinamis per level) ──
            // 'validasi prosedur level 1',
            // 'validasi prosedur level 2',
            // 'validasi prosedur level 3',
            // 'validasi prosedur level 4',

            // 'lihat antrean prosedur',     
            // 'lihat rekap prosedur',        
            // 'kelola validator prosedur',  
            // 'kelola template chat',

            // Troubleshoot Tracking
            // 'kelola troubleshoot',
            // 'lihat troubleshoot',
            // 'filter organization'

            // ── PERSETUJUAN (konten publik) ──
            'lihat persetujuan',

            // ── PENDAFTARAN BARU (inbox customer) ──
            'lihat pendaftaran baru',
            'assign pendaftaran',
            'tolak pendaftaran',
            'spam pemasangan',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
    }
}
