<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Jika kolom sudah ada dengan enum lama, ubah definisinya
        if (Schema::hasColumn('customers', 'email_verify_at')) {
            // MySQL: ubah enum value
            DB::statement("ALTER TABLE customers MODIFY COLUMN email_verify_at ENUM('register','not_register') NULL DEFAULT NULL");
        } else {
            Schema::table('customers', function (Blueprint $table) {
                $table->enum('email_verify_at', ['register', 'not_register'])
                      ->nullable()
                      ->default(null)
                      ->after('email');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('customers', 'email_verify_at')) {
            Schema::table('customers', function (Blueprint $table) {
                $table->dropColumn('email_verify_at');
            });
        }
    }
};
