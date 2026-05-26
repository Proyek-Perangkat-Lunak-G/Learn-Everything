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
        if (!Schema::hasColumn('tutors', 'wallet_pending')) {
            Schema::table('tutors', function (Blueprint $table) {
                $table->decimal('wallet_pending', 12, 2)->default(0)->after('bank_account_number');
                $table->decimal('wallet_available', 12, 2)->default(0)->after('wallet_pending');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tutors', function (Blueprint $table) {
            $table->dropColumn(['wallet_pending', 'wallet_available']);
        });
    }
};
