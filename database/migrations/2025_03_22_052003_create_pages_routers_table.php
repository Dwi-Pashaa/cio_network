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
        Schema::create('pages_routers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pages_id')->references('id')->on('pages')->onDelete('CASCADE');
            $table->foreignId('routers_id')->references('id')->on('router_networks')->onDelete('CASCADE');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pages_routers');
    }
};
