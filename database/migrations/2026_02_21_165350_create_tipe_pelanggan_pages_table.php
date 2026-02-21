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
        Schema::create('tipe_pelanggan_pages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pages_id');
            $table->unsignedBigInteger('tipe_pelanggan_id');
            $table->timestamps();

            $table->foreign('pages_id')->references('id')->on('pages')->onDelete('cascade');
            $table->foreign('tipe_pelanggan_id')->references('id')->on('customer_types')->onDelete('cascade');

            $table->index(['pages_id', 'tipe_pelanggan_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipe_pelanggan_pages');
    }
};
