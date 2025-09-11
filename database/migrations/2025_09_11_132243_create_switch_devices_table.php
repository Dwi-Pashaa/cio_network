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
        Schema::create('switch_device', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->references('id')->on('customers')->onDelete('CASCADE');
            $table->foreignId('type_old_id')->references('id')->on('customer_types')->onDelete('CASCADE');
            $table->foreignId('router_old_id')->references('id')->on('router_networks')->onDelete('CASCADE');
            $table->string('mac_address_old');
            $table->foreignId('type_new_id')->references('id')->on('customer_types')->onDelete('CASCADE');
            $table->foreignId('router_new_id')->references('id')->on('router_networks')->onDelete('CASCADE');
            $table->string('mac_address_new');
            $table->enum('status', ['active', 'deactive'])->default('deactive');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('switch_device');
    }
};
