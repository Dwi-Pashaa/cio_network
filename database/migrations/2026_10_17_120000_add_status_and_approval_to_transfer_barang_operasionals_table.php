<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('transfer_barang_operasionals', function (Blueprint $table) {
            $table->string('status', 30)->default('menunggu_konfirmasi')->after('tanggal_transfer');
            $table->dateTime('tanggal_konfirmasi')->nullable()->after('status');
            $table->text('catatan_konfirmasi')->nullable()->after('tanggal_konfirmasi');
            $table->boolean('wa_notif_sent')->default(false)->after('catatan_konfirmasi');
        });

        // Set status data lama menjadi 'diterima'
        DB::table('transfer_barang_operasionals')->update([
            'status' => 'diterima',
            'tanggal_konfirmasi' => DB::raw('tanggal_transfer')
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transfer_barang_operasionals', function (Blueprint $table) {
            $table->dropColumn(['status', 'tanggal_konfirmasi', 'catatan_konfirmasi', 'wa_notif_sent']);
        });
    }
};
