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
        Schema::create('prosedur_spams', function (Blueprint $table) {
            $table->id();

            $table->foreignId('customer_id')
                ->constrained('customers')
                ->onDelete('cascade');

            $table->foreignId('submitted_by')
                ->constrained('users')
                ->comment('Teknisi lapangan yang mengajukan prosedur');

            $table->foreignId('organization_id')
                ->nullable()
                ->constrained('organization')
                ->nullOnDelete();

            // Jenis prosedur: 'onu-router' | 'pergantian-layanan' | 'pemutusan'
            $table->string('prosedur_type');

            // Semua data input form disimpan sebagai JSON
            // (alasan pemutusan, path bukti foto, konfigurasi baru, dsb.)
            $table->json('payload')->nullable();

            // Status keseluruhan: 'pending' | 'approved' | 'rejected'
            $table->string('status')->default('pending');

            // Penolakan
            $table->foreignId('rejected_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->timestamp('rejected_at')->nullable();
            $table->text('reject_reason')->nullable();

            // Eksekusi (diisi sistem saat semua level approve)
            $table->foreignId('executed_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->timestamp('executed_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prosedur_spams');
    }
};
