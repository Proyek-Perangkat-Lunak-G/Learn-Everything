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
        Schema::table('users', function (Blueprint $table) {
            $table->decimal('wallet_pending', 12, 2)->default(0)->after('photo'); // Earnings from product sales
            $table->decimal('wallet_available', 12, 2)->default(0)->after('wallet_pending'); // Available for withdrawal
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['wallet_pending', 'wallet_available']);
        });
    }
};
