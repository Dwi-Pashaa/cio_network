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
            'lihat tipe barang operasional',
            'tambah tipe barang operasional',
            'edit tipe barang operasional',
            'hapus tipe barang operasional',
            'lihat barang operasional',
            'tambah barang operasional',
            'edit barang operasional',
            'hapus barang operasional',
            'distribusi barang operasional',
            'transfer barang teknisi',
            'lihat riwayat transfer barang',
        ];

        foreach ($permissions as $permName) {
            $permission = Permission::firstOrCreate([
                'name' => $permName,
                'guard_name' => 'web'
            ]);

            // Assign to internal organizations if any
            $organizations = Organization::all();
            foreach ($organizations as $org) {
                OrganizationPermission::firstOrCreate([
                    'organization_id' => $org->id,
                    'permission_id'   => $permission->id,
                ]);
            }

            // Assign to Admin role
            $adminRoles = Role::where('name', 'Admin')->get();
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
            'lihat tipe barang operasional',
            'tambah tipe barang operasional',
            'edit tipe barang operasional',
            'hapus tipe barang operasional',
            'lihat barang operasional',
            'tambah barang operasional',
            'edit barang operasional',
            'hapus barang operasional',
            'distribusi barang operasional',
            'transfer barang teknisi',
            'lihat riwayat transfer barang',
        ];

        Permission::whereIn('name', $permissions)->delete();
    }
};
