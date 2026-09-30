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
        Schema::create('notification_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable()->unique()->comment('Null for global/default setting');
            $table->unsignedBigInteger('organization_id')->nullable();
            $table->enum('channel', ['whatsapp', 'email', 'both', 'none'])->default('both');
            $table->boolean('notify_prosedur')->default(true);
            $table->boolean('notify_troubleshoot')->default(true);
            $table->boolean('notify_pendaftaran')->default(true);
            $table->boolean('notify_complain')->default(true);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('organization_id')->references('id')->on('organization')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_settings');
    }
};
