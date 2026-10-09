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
        Schema::table('vlan_networks', function (Blueprint $table) {
            $table->string('ip_address')->nullable()->after('name');
            $table->boolean('support_pppoe')->default(false)->after('ip_address');
            $table->boolean('support_voucher')->default(false)->after('support_pppoe');
            $table->foreignId('regencie_id')->nullable()->after('support_voucher')->constrained('regencies')->nullOnDelete();
            $table->foreignId('district_id')->nullable()->after('regencie_id')->constrained('districts')->nullOnDelete();
            $table->foreignId('village_id')->nullable()->after('district_id')->constrained('villages')->nullOnDelete();
            $table->foreignId('hometown_id')->nullable()->after('village_id')->constrained('home_towns')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vlan_networks', function (Blueprint $table) {
            $table->dropForeign(['regencie_id']);
            $table->dropForeign(['district_id']);
            $table->dropForeign(['village_id']);
            $table->dropForeign(['hometown_id']);
            $table->dropColumn([
                'ip_address',
                'support_pppoe',
                'support_voucher',
                'regencie_id',
                'district_id',
                'village_id',
                'hometown_id',
            ]);
        });
    }
};
