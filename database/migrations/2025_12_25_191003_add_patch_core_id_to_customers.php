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
            $table->unsignedBigInteger('patch_core_id')->nullable();
            $table->foreign('patch_core_id')->references('id')->on('patch_core')->onDelete('set null');
            $table->index('patch_core_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->unsignedBigInteger('patch_core_id')->nullable();
            $table->foreign('patch_core_id')->references('id')->on('patch_core')->onDelete('set null');
            $table->index('patch_core_id');
        });
    }
};
