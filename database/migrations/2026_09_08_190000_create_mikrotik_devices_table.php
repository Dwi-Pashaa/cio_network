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
        Schema::create('mikrotik_devices', function (Blueprint $table) {
            $table->id();
            $table->string('mac_address')->unique();
            $table->string('ip_address')->nullable()->index();
            $table->string('host_name')->nullable()->index();
            $table->string('device_type')->default('dynamic'); // dynamic / static
            $table->string('status')->default('bound'); // bound, waiting, offered, etc.
            $table->boolean('is_active')->default(false)->index();
            $table->string('source')->nullable(); // DHCP Lease, ARP Table, Hotspot Host
            $table->string('expires_after')->nullable();
            $table->string('interface')->nullable();
            $table->text('comment')->nullable();
            $table->timestamp('last_seen_at')->nullable();
            $table->json('raw_payload')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mikrotik_devices');
    }
};
