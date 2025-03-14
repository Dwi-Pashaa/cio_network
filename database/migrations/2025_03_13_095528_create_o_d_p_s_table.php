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
        Schema::create('odp_networks', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->foreignId('hometowns_id')->references('id')->on('home_towns')->onDelete('CASCADE');
            $table->foreignId('rts_id')->references('id')->on('home_towns')->onDelete('CASCADE');
            $table->foreignId('rws_id')->references('id')->on('home_towns')->onDelete('CASCADE');
            $table->string('home_odc');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('o_d_p_s');
    }
};
