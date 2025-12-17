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
        Schema::table('mac_address', function (Blueprint $table) {
            // $table->unsignedBigInteger('user_id')->nullable()->after('id')->default(1);
            // $table->foreign('user_id')
            //     ->references('id')
            //     ->on('users')
            //     ->nullOnDelete();

            $table->unsignedBigInteger('router_id')->nullable()->after('user_id');
            $table->foreign('router_id')
                ->references('id')
                ->on('router_networks')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mac_address', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->after('id');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->nullOnDelete();

            $table->unsignedBigInteger('router_id')->nullable()->after('user_id');
            $table->foreign('router_id')
                ->references('id')
                ->on('router_networks')
                ->nullOnDelete();
        });
    }
};
