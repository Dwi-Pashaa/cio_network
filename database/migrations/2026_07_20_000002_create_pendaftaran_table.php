<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pendaftaran', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique();
            $table->string('nama');
            $table->string('email')->nullable();
            $table->string('no_telepon');
            $table->foreignId('tipe_layanan_id')->nullable()->constrained('customer_types')->nullOnDelete();
            $table->foreignId('hometowns_id')->nullable()->constrained('home_towns')->nullOnDelete();
            $table->foreignId('villages_id')->nullable()->constrained('villages')->nullOnDelete();
            $table->foreignId('pages_id')->nullable()->constrained('pages')->nullOnDelete();
            $table->foreignId('organization_id')->constrained('organization')->onDelete('CASCADE');
            $table->longText('tanda_tangan_customer')->nullable();
            $table->foreignId('persetujuan_id')->nullable()->constrained('persetujuan')->nullOnDelete();
            $table->enum('status', ['pending', 'assigned'])->default('pending');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('assigned_at')->nullable();
            $table->text('catatan_admin')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pendaftaran');
    }
};
