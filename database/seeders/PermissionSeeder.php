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
            'pergantian perangkat',
            'pemutusan layanan',
            'pergantian layanan',

            // ── VALIDASI PROSEDUR (dinamis per level) ──
            'validasi prosedur level 1',
            'validasi prosedur level 2',
            'validasi prosedur level 3',
            'validasi prosedur level 4',

            // ── AKSES MENU PROSEDUR ──
            'lihat antrean prosedur',      // Melihat daftar antrean validasi
            'lihat rekap prosedur',        // Melihat log historis rekap prosedur
            'kelola validator prosedur',   // Konfigurasi mapping level validator
            'kelola template chat'
            // 'lihat log aktivitas'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
    }
}
