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
        Schema::create('user_patch_core', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('patch_core_id');
            $table->integer('total');

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('patch_core_id')->references('id')->on('patch_core')->onDelete('cascade');

            $table->unique(['user_id', 'patch_core_id']);
            $table->index(['user_id', 'patch_core_id']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_patch_core');
    }
};
