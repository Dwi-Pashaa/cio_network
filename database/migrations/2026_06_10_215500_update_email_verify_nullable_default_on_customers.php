<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasColumn('customers', 'email_verify_at')) {
            DB::statement("ALTER TABLE customers MODIFY COLUMN email_verify_at ENUM('register','not_register') NULL DEFAULT NULL");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('customers', 'email_verify_at')) {
            DB::statement("ALTER TABLE customers MODIFY COLUMN email_verify_at ENUM('register','not_register') NOT NULL DEFAULT 'not_register'");
        }
    }
};
