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
        Schema::create('user_plc', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('plc_id');
            $table->integer('total');

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('plc_id')->references('id')->on('plc')->onDelete('cascade');

            $table->unique(['user_id', 'plc_id']);
            $table->index(['user_id', 'plc_id']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_plc');
    }
};
