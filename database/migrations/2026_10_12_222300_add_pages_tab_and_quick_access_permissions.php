<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            // Tab Halaman & Aksi Halaman Lengkap
            'lihat tab halaman lama',
            'lihat tab halaman lengkap',
            'buat halaman lengkap',
            'edit halaman lengkap',
            'hapus halaman lengkap',
            'kelola user halaman',
            'buat vlan dari halaman',

            // Akses Cepat (Quick Access)
            'lihat akses cepat halaman',
            'akses cepat router',
            'akses cepat vlan',
            'akses cepat olt',
            'akses cepat odc',
            'akses cepat odp',
            'akses cepat server',
            'akses cepat mic radius',
            'akses cepat mac address',
            'akses cepat patch core',
            'akses cepat plc',
            'akses cepat stock router',
            'akses cepat stock patch core',
            'akses cepat stock plc',
            'akses cepat barang operasional',
            'akses cepat tipe barang operasional',
            'akses cepat riwayat transfer barang',
            'akses cepat tipe paket',
            'akses cepat tipe pembayaran',
        ];

        foreach ($permissions as $permName) {
            $permission = Permission::firstOrCreate([
                'name' => $permName,
                'guard_name' => 'web',
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

        // Khusus Teknisi: berikan izin melihat tab halaman lama
        $teknisiRoles = Role::where('name', 'Teknisi')->get();
        foreach ($teknisiRoles as $teknisiRole) {
            if (!$teknisiRole->hasPermissionTo('lihat tab halaman lama')) {
                $teknisiRole->givePermissionTo('lihat tab halaman lama');
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $permissions = [
            'lihat tab halaman lama',
            'lihat tab halaman lengkap',
            'buat halaman lengkap',
            'edit halaman lengkap',
            'hapus halaman lengkap',
            'kelola user halaman',
            'buat vlan dari halaman',

            'lihat akses cepat halaman',
            'akses cepat router',
            'akses cepat vlan',
            'akses cepat olt',
            'akses cepat odc',
            'akses cepat odp',
            'akses cepat server',
            'akses cepat mic radius',
            'akses cepat mac address',
            'akses cepat patch core',
            'akses cepat plc',
            'akses cepat stock router',
            'akses cepat stock patch core',
            'akses cepat stock plc',
            'akses cepat barang operasional',
            'akses cepat tipe barang operasional',
            'akses cepat riwayat transfer barang',
            'akses cepat tipe paket',
            'akses cepat tipe pembayaran',
        ];

        Permission::whereIn('name', $permissions)->delete();
    }
};
