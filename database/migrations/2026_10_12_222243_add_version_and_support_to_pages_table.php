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
        Schema::table('pages', function (Blueprint $table) {
            $table->enum('version', ['legacy', 'complete'])->default('legacy')->after('type');
            $table->boolean('support_pppoe')->default(true)->after('is_persetujuan');
            $table->boolean('support_voucher')->default(true)->after('support_pppoe');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn(['version', 'support_pppoe', 'support_voucher']);
        });
    }
};
