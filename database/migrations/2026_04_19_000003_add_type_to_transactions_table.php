<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Consolidated into create_transactions_table migration
        // This migration is kept for compatibility but does nothing on fresh install
        if (!Schema::hasColumn('transactions', 'type')) {
            Schema::table('transactions', function (Blueprint $table) {
                $table->string('type')->default('purchase')->after('payment_method');
                $table->text('notes')->nullable()->after('type');
            });
        }
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['type', 'notes']);
        });
    }
};
