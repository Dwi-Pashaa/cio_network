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
        Schema::create('prosedur_spam_validations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('prosedur_spam_id')
                ->constrained('prosedur_spams')
                ->onDelete('cascade');

            // Level validasi: 1, 2, 3, 4 (dinamis sesuai config/prosedur_levels.php)
            $table->unsignedTinyInteger('level')
                ->comment('Nomor urut level validasi, misal: 1, 2, 3, 4');

            // Label level disalin dari config saat request dibuat
            // (agar rekap historis tetap akurat meski config berubah di kemudian hari)
            $table->string('level_label')
                ->comment('Nama human-readable checkpoint, misal: Admin, OLT, ONC, Mix Radius');

            // Permission yang dibutuhkan untuk memvalidasi di level ini
            $table->string('required_permission')
                ->comment('misal: validasi prosedur level 1');

            // Status checkpoint ini: 'pending' | 'approved' | 'rejected'
            $table->string('status')->default('pending');

            // User yang memvalidasi (bisa null jika belum)
            $table->foreignId('validated_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->timestamp('validated_at')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();

            // Setiap prosedur hanya boleh punya satu row per level
            $table->unique(['prosedur_spam_id', 'level']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prosedur_spam_validations');
    }
};
