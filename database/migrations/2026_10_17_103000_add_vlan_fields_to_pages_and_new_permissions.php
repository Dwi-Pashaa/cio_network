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
        // 1. Add vlan_name, server_name, ip_address columns to pages table
        Schema::table('pages', function (Blueprint $table) {
            if (!Schema::hasColumn('pages', 'vlan_name')) {
                $table->string('vlan_name')->nullable()->after('name');
            }
            if (!Schema::hasColumn('pages', 'server_name')) {
                $table->string('server_name')->nullable()->after('vlan_name');
            }
            if (!Schema::hasColumn('pages', 'ip_address')) {
                $table->string('ip_address')->nullable()->after('server_name');
            }
        });

        // 2. Add granular permissions
        $permissions = [
            'input halaman nama vlan',
            'input halaman server',
            'input halaman ip address',
        ];

        $organizations = Organization::all();
        $adminRoles = Role::where('name', 'Admin')->get();

        foreach ($permissions as $permName) {
            $permission = Permission::firstOrCreate([
                'name'       => $permName,
                'guard_name' => 'web',
            ]);

            foreach ($organizations as $org) {
                OrganizationPermission::firstOrCreate([
                    'organization_id' => $org->id,
                    'permission_id'   => $permission->id,
                ]);
            }

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
        Schema::table('pages', function (Blueprint $table) {
            if (Schema::hasColumn('pages', 'ip_address')) {
                $table->dropColumn('ip_address');
            }
            if (Schema::hasColumn('pages', 'server_name')) {
                $table->dropColumn('server_name');
            }
            if (Schema::hasColumn('pages', 'vlan_name')) {
                $table->dropColumn('vlan_name');
            }
        });

        $permissions = [
            'input halaman nama vlan',
            'input halaman server',
            'input halaman ip address',
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
