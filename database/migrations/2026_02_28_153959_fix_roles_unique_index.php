<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('roles', function (Blueprint $table) {

            // Drop unique lama
            $table->dropUnique('roles_name_guard_name_unique');

            // Tambah unique baru
            $table->unique(
                ['name', 'guard_name', 'organization_id'],
                'roles_name_guard_org_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('roles', function (Blueprint $table) {

            $table->dropUnique('roles_name_guard_org_unique');

            $table->unique(
                ['name', 'guard_name'],
                'roles_name_guard_name_unique'
            );
        });
    }
};
