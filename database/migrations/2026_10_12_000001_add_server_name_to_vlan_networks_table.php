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
        Schema::table('vlan_networks', function (Blueprint $table) {
            if (!Schema::hasColumn('vlan_networks', 'server_name')) {
                $table->string('server_name')->nullable()->after('name');
            }
        });

        $permissions = [
            'input vlan server',
            'lihat vlan server',
        ];

        foreach ($permissions as $permName) {
            $permission = Permission::firstOrCreate([
                'name' => $permName,
                'guard_name' => 'web',
            ]);

            $organizations = Organization::all();
            foreach ($organizations as $org) {
                OrganizationPermission::firstOrCreate([
                    'organization_id' => $org->id,
                    'permission_id'   => $permission->id,
                ]);
            }

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
        Schema::table('vlan_networks', function (Blueprint $table) {
            if (Schema::hasColumn('vlan_networks', 'server_name')) {
                $table->dropColumn('server_name');
            }
        });

        Permission::whereIn('name', ['input vlan server', 'lihat vlan server'])->delete();
    }
};
