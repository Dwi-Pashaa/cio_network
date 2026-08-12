<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('persetujuan', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->longText('konten');
            $table->boolean('is_active')->default(false);
            $table->foreignId('organization_id')->constrained('organization')->onDelete('CASCADE');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('persetujuan');
    }
};
