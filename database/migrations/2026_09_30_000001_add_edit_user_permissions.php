<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $permissions = [
            'edit username',
            'edit nama',
            'edit email',
            'edit no telephone',
            'edit level aksess',
            'edit penempatan',
            'edit aksess data halaman',
            'edit aksess router',
            'edit aksess patch core',
            'edit aksess mic radius',
            'edit keamanan',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $permissions = [
            'edit username',
            'edit nama',
            'edit email',
            'edit no telephone',
            'edit level aksess',
            'edit penempatan',
            'edit aksess data halaman',
            'edit aksess router',
            'edit aksess patch core',
            'edit aksess mic radius',
            'edit keamanan',
        ];

        Permission::whereIn('name', $permissions)->delete();
    }
};
