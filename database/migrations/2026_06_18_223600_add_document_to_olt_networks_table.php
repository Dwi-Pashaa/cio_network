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
        Schema::table('olt_networks', function (Blueprint $table) {
            $table->string('document')->nullable()->after('foto_ktp_penanggung_jawab');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('olt_networks', function (Blueprint $table) {
            $table->dropColumn('document');
        });
    }
};
