<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mic_radius', function (Blueprint $table) {
            $table->string('mix_password')->nullable()->after('longitude');
        });
    }

    public function down(): void
    {
        Schema::table('mic_radius', function (Blueprint $table) {
            $table->dropColumn('mix_password');
        });
    }
};
