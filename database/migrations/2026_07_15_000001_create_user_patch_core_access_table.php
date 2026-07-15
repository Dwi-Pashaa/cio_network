<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_patch_core_access', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('patch_core_id');
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('CASCADE');

            $table->foreign('patch_core_id')
                ->references('id')
                ->on('patch_core')
                ->onDelete('CASCADE');

            $table->unique(['user_id', 'patch_core_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_patch_core_access');
    }
};
