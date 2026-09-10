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
        Schema::table('customers', function (Blueprint $table) {
            $table->index(['status', 'organization_id', 'created_at'], 'idx_customers_status_org_created');
            $table->index('mac_address', 'idx_customers_mac_address');
            $table->index(['email_verify_at', 'wa_verifiy_at'], 'idx_customers_verification');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropIndex('idx_customers_status_org_created');
            $table->dropIndex('idx_customers_mac_address');
            $table->dropIndex('idx_customers_verification');
        });
    }
};
