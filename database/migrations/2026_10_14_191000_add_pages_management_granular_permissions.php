<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\Organization;
use App\Models\OrganizationPermission;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $permissions = [
            // Kolom Tabel
            'lihat kolom halaman nama',
            'lihat kolom halaman vlan',
            'lihat kolom halaman server',
            'lihat kolom halaman ip address',
            'lihat kolom halaman support',
            'lihat kolom halaman persetujuan',
            'lihat kolom halaman foto ktp',
            'lihat kolom halaman cek kontak',
            'lihat kolom halaman wilayah',
            'lihat kolom halaman olt',
            'lihat kolom halaman mix radius',
            'lihat kolom halaman tipe paket',
            'lihat kolom halaman tipe pembayaran',
            'lihat kolom halaman user',

            // Input Form (Wizard & Edit)
            'input halaman judul',
            'input halaman deskripsi',
            'input halaman no telp',
            'input halaman password',
            'input halaman wilayah',
            'input halaman opsi ktp',
            'input halaman opsi persetujuan',
            'input halaman opsi cek kontak',
            'input halaman tipe pelanggan',
            'input halaman vlan',
            'input halaman support',
            'input halaman odc',
            'input halaman odp',
            'input halaman olt',
            'input halaman mic radius',
            'input halaman paket',
            'input halaman pembayaran',
            'input halaman user ditugaskan',
        ];

        $organizations = Organization::all();
        $adminRoles = Role::where('name', 'Admin')->get();

        foreach ($permissions as $permName) {
            $permission = Permission::firstOrCreate([
                'name'       => $permName,
                'guard_name' => 'web',
            ]);

            // Assign to internal / all organizations
            foreach ($organizations as $org) {
                OrganizationPermission::firstOrCreate([
                    'organization_id' => $org->id,
                    'permission_id'   => $permission->id,
                ]);
            }

            // Assign to Admin role
            foreach ($adminRoles as $adminRole) {
                if (!$adminRole->hasPermissionTo($permName)) {
                    $adminRole->givePermissionTo($permission);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $permissions = [
            'lihat kolom halaman nama',
            'lihat kolom halaman vlan',
            'lihat kolom halaman server',
            'lihat kolom halaman ip address',
            'lihat kolom halaman support',
            'lihat kolom halaman persetujuan',
            'lihat kolom halaman foto ktp',
            'lihat kolom halaman cek kontak',
            'lihat kolom halaman wilayah',
            'lihat kolom halaman olt',
            'lihat kolom halaman mix radius',
            'lihat kolom halaman tipe paket',
            'lihat kolom halaman tipe pembayaran',
            'lihat kolom halaman user',
            'input halaman judul',
            'input halaman deskripsi',
            'input halaman no telp',
            'input halaman password',
            'input halaman wilayah',
            'input halaman opsi ktp',
            'input halaman opsi persetujuan',
            'input halaman opsi cek kontak',
            'input halaman tipe pelanggan',
            'input halaman vlan',
            'input halaman support',
            'input halaman odc',
            'input halaman odp',
            'input halaman olt',
            'input halaman mic radius',
            'input halaman paket',
            'input halaman pembayaran',
            'input halaman user ditugaskan',
        ];

        foreach ($permissions as $permName) {
            $permission = Permission::where('name', $permName)->first();
            if ($permission) {
                OrganizationPermission::where('permission_id', $permission->id)->delete();
                $permission->delete();
            }
        }
    }
};
