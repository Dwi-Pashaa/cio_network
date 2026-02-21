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
        Schema::table('customers', function (Blueprint $table) {
            $table->unsignedBigInteger('tipe_pelanggan_id')->nullable();
            $table->foreign('tipe_pelanggan_id')->references('id')->on('customer_types')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->unsignedBigInteger('tipe_pelanggan_id')->nullable();
            $table->foreign('tipe_pelanggan_id')->references('id')->on('customer_types')->onDelete('set null');
        });
    }
};
