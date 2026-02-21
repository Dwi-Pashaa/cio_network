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
            // 'lihat stock router',
            // 'lihat stock patch core',
            // 'lihat stock plc',
            // 'lihat patch core',
            // 'tambah patch core',
            // 'edit patch core',
            // 'hapus patch core',
            // 'lihat plc',
            // 'tambah plc',
            // 'edit plc',
            // 'hapus plc',
            // 'copy pelanggan',
            // 'lihat log wablas',
            'lihat tipe pelanggan',
            'tambah tipe pelanggan',
            'edit tipe pelanggan',
            'hapus tipe pelanggan',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
    }
}
