<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Daftar Role
        $roles = [
            "Admin",
            "Lapangan"
        ];

        foreach ($roles as $value) {
            Role::firstOrCreate(['name' => $value]);
        }

        $permissions = [
            // Manajemen Pelanggan (Customer)
            'buat pelanggan', 'lihat pelanggan', 'ubah pelanggan', 'hapus pelanggan',
        
            // Manajemen Router
            'buat router', 'lihat router', 'ubah router', 'hapus router',
        
            // Manajemen VLAN
            'buat vlan', 'lihat vlan', 'ubah vlan', 'hapus vlan',
        
            // Manajemen ODC
            'buat odc', 'lihat odc', 'ubah odc', 'hapus odc',
        
            // Manajemen ODP
            'buat odp', 'lihat odp', 'ubah odp', 'hapus odp',
        
            // Manajemen OLT
            'buat olt', 'lihat olt', 'ubah olt', 'hapus olt',
        
            // Manajemen Wilayah Administratif
            'buat kabupaten', 'lihat kabupaten', 'ubah kabupaten', 'hapus kabupaten',
            'buat kecamatan', 'lihat kecamatan', 'ubah kecamatan', 'hapus kecamatan',
            'buat kampung', 'lihat kampung', 'ubah kampung', 'hapus kampung',
            'buat desa', 'lihat desa', 'ubah desa', 'hapus desa',
            'buat rt', 'lihat rt', 'ubah rt', 'hapus rt',
            'buat rw', 'lihat rw', 'ubah rw', 'hapus rw',
            'download excel'
        ];
        

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $adminRole = Role::where('name', 'Admin')->first();
        if ($adminRole) {
            $adminRole->syncPermissions($permissions);
        }
    }
}
