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
        Schema::table('transactions', function (Blueprint $table) {
            $table->foreignId('address_id')->nullable()->after('user_id')->constrained()->nullOnDelete();
            $table->text('shipping_address')->nullable()->after('address_id');
            $table->string('shipping_phone')->nullable()->after('shipping_address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['address_id']);
            $table->dropColumn(['address_id', 'shipping_address', 'shipping_phone']);
        });
    }
};
