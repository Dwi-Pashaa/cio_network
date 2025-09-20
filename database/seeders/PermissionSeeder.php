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
            // 'lihat halaman', 'buat halaman', 'edit halaman', 'hapus halaman',
            'lihat tipe paket',
            'buat tipe paket',
            'edit tipe paket',
            'hapus tipe paket',
            'lihat tipe pembayaran',
            'buat tipe pembayaran',
            'edit tipe paket',
            'hapus tipe paket',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
    }
}
