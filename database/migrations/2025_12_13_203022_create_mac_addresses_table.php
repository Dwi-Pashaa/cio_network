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
        Schema::create('mac_address', function (Blueprint $table) {
            $table->id();
            $table->string('mac_address')->unique();
            $table->enum('status', ['available', 'used', 'blocked'])->default('available');
            $table->enum('status_device', ['rusak', 'baik']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mac_address');
    }
};
