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
            // 'lihat mic radius',
            // 'buat mic radius',
            // 'edit mic radius',
            // 'hapus mic radius',
            // 'lihat tipe paket',
            // 'buat tipe paket',
            // 'edit tipe paket',
            // 'hapus tipe paket',
            // 'lihat tipe pembayaran',
            // 'buat tipe pembayaran',
            // 'lihat barang',
            // 'buat barang',
            // 'edit barang',
            // 'hapus barang',
            // 'lihat histori pemasangan'
            // 'chatting',
            // 'view all chatting',
            // 'view inbox chatting',
            // 'tambah stock'
            'tambah mac address',
            'lihat mac address',
            'hapus mac address',
            'edit mac address',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
    }
}
