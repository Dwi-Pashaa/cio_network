<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_router_access', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('router_id');
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('CASCADE');

            $table->foreign('router_id')
                ->references('id')
                ->on('router_networks')
                ->onDelete('CASCADE');

            $table->unique(['user_id', 'router_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_router_access');
    }
};
