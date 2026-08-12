<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pendaftaran', function (Blueprint $table) {
            $table->foreignId('paket_id')->nullable()->after('pages_id')->constrained('paket')->nullOnDelete();
            $table->foreignId('price_id')->nullable()->after('paket_id')->constrained('price')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('pendaftaran', function (Blueprint $table) {
            $table->dropForeign(['pendaftaran_paket_id_foreign']);
            $table->dropColumn('paket_id');
            $table->dropForeign(['pendaftaran_price_id_foreign']);
            $table->dropColumn('price_id');
        });
    }
};
