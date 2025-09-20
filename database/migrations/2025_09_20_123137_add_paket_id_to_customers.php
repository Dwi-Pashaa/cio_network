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
            $table->foreignId('paket_id')->nullable()->references('id')->on('paket')->onDelete('CASCADE');
            $table->foreignId('price_id')->nullable()->references('id')->on('price')->onDelete('CASCADE');
            $table->string('name_wifi')->nullable();
            $table->string('password_wifi')->nullable();
            $table->string('pppoe_username')->nullable();
            $table->string('pppoe_password')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->foreignId('paket_id')->nullable()->references('id')->on('paket')->onDelete('CASCADE');
            $table->foreignId('price_id')->nullable()->references('id')->on('price')->onDelete('CASCADE');
        });
    }
};
