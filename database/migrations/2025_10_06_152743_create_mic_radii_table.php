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
        Schema::create('mic_radius', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hometowns_id')->references('id')->on('home_towns')->onDelete('CASCADE');
            $table->string('code');
            $table->string('name');
            $table->string('latitude');
            $table->string('longitude');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mic_radius');
    }
};
