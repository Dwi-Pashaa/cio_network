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
            'download qrcode',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
    }
}
