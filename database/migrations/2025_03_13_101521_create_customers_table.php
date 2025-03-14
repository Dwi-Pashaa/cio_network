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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('mac_address');
            $table->string('telp');
            $table->string('email');
            $table->foreignId('routers_id')->references('id')->on('router_networks')->onDelete('CASCADE');
            $table->foreignId('types_id')->references('id')->on('customer_types')->onDelete('CASCADE');
            $table->foreignId('hometowns_id')->references('id')->on('home_towns')->onDelete('CASCADE');
            $table->foreignId('rts_id')->references('id')->on('rts')->onDelete('CASCADE');
            $table->foreignId('rws_id')->references('id')->on('rws')->onDelete('CASCADE');
            $table->foreignId('villages_id')->references('id')->on('villages')->onDelete('CASCADE');
            $table->foreignId('districts_id')->references('id')->on('districts')->onDelete('CASCADE');
            $table->foreignId('regencies_id')->references('id')->on('regencies')->onDelete('CASCADE');
            $table->foreignId('odcs_id')->references('id')->on('odc_networks')->onDelete('CASCADE');
            $table->foreignId('odps_id')->references('id')->on('odp_networks')->onDelete('CASCADE');
            $table->foreignId('olts_id')->references('id')->on('olt_networks')->onDelete('CASCADE');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
