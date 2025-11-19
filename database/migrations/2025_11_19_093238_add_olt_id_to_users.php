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
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('olt_id')->nullable()->after('id');

            $table->foreign('olt_id')
                ->references('id')
                ->on('olt_networks')
                ->onDelete('set null');

            $table->unsignedBigInteger('mic_radius_id')->nullable()->after('id');

            $table->foreign('mic_radius_id')
                ->references('id')
                ->on('mic_radius')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
