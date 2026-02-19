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
        Schema::create('wablas_report', function (Blueprint $table) {
            $table->id();
            $table->string('wablas_id')->unique();
            $table->string('from');
            $table->string('to');
            $table->longText('message');
            $table->enum('status', ['sent', 'pending', 'read', 'cancel', 'delivered']);
            $table->timestamp('date');
            $table->timestamp('sent_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wablas_report');
    }
};
