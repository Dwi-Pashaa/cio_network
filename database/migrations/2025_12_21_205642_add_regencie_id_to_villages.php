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
        Schema::table('villages', function (Blueprint $table) {
            $table->unsignedBigInteger('regencie_id')->after('code')->nullable()->after('id');
            $table->foreign('regencie_id')->references('id')->on('regencies')->onDelete('set null');
            $table->index('regencie_id');

            $table->unsignedBigInteger('district_id')->after('code')->nullable()->after('regencie_id');
            $table->foreign('district_id')->references('id')->on('districts')->onDelete('set null');
            $table->index('district_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('villages', function (Blueprint $table) {
            $table->unsignedBigInteger('regencie_id')->after('code')->nullable()->after('id');
            $table->foreign('regencie_id')->references('id')->on('regencies')->onDelete('set null');
            $table->index('regencie_id');

            $table->unsignedBigInteger('district_id')->after('code')->nullable()->after('regencie_id');
            $table->foreign('district_id')->references('id')->on('districts')->onDelete('set null');
            $table->index('district_id');
        });
    }
};
