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
        Schema::table('pages', function (Blueprint $table) {
            if (!Schema::hasColumn('pages', 'persetujuan_id')) {
                $table->foreignId('persetujuan_id')
                    ->nullable()
                    ->after('is_persetujuan')
                    ->constrained('persetujuan')
                    ->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            if (Schema::hasColumn('pages', 'persetujuan_id')) {
                $table->dropForeign(['persetujuan_id']);
                $table->dropColumn('persetujuan_id');
            }
        });
    }
};
