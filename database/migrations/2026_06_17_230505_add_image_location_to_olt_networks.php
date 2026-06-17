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
            $table->string('foto_lokasi')->nullable()->after('link');
            $table->string('foto_ktp_pemilik_tempat')->nullable()->after('foto_lokasi');
            $table->string('foto_ktp_penanggung_jawab')->nullable()->after('foto_ktp_pemilik_tempat');
            $table->longText('address')->nullable()->after('foto_ktp_penanggung_jawab');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('olt_networks', function (Blueprint $table) {
            $table->dropColumn(['foto_lokasi', 'foto_ktp_pemilik_tempat', 'foto_ktp_penanggung_jawab', 'address']);
        });
    }
};
