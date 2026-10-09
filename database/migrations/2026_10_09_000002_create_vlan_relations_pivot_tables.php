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
        // 1. Pivot VLAN - OLT
        Schema::create('vlan_olts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vlan_id')->constrained('vlan_networks')->cascadeOnDelete();
            $table->foreignId('olt_id')->constrained('olt_networks')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['vlan_id', 'olt_id']);
        });

        // 2. Pivot VLAN - Mix Radius
        Schema::create('vlan_mix_radiuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vlan_id')->constrained('vlan_networks')->cascadeOnDelete();
            $table->foreignId('mic_radius_id')->constrained('mic_radius')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['vlan_id', 'mic_radius_id']);
        });

        // 3. Pivot VLAN - Paket
        Schema::create('vlan_pakets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vlan_id')->constrained('vlan_networks')->cascadeOnDelete();
            $table->foreignId('paket_id')->constrained('paket')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['vlan_id', 'paket_id']);
        });

        // 4. Pivot VLAN - Price / Tipe Pembayaran
        Schema::create('vlan_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vlan_id')->constrained('vlan_networks')->cascadeOnDelete();
            $table->foreignId('price_id')->constrained('price')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['vlan_id', 'price_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vlan_prices');
        Schema::dropIfExists('vlan_pakets');
        Schema::dropIfExists('vlan_mix_radiuses');
        Schema::dropIfExists('vlan_olts');
    }
};
