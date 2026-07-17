<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_mic_radius_access', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('mic_radius_id');
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('CASCADE');

            $table->foreign('mic_radius_id')
                ->references('id')
                ->on('mic_radius')
                ->onDelete('CASCADE');

            $table->unique(['user_id', 'mic_radius_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_mic_radius_access');
    }
};
