<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('price', function (Blueprint $table) {
            $table->boolean('use_bukti_bayar')->default(false)->after('is_public');
        });
    }

    public function down(): void
    {
        Schema::table('price', function (Blueprint $table) {
            $table->dropColumn('use_bukti_bayar');
        });
    }
};
