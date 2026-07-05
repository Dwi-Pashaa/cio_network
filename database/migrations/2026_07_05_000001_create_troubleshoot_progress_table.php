<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('troubleshoot_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('troubleshoot_id')->constrained()->cascadeOnDelete();
            $table->tinyInteger('step');
            $table->enum('status', ['pending', 'completed'])->default('pending');
            $table->string('photo')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('address')->nullable();
            $table->timestamps();

            $table->unique(['troubleshoot_id', 'step']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('troubleshoot_progress');
    }
};
