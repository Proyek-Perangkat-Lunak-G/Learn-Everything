<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('transaction_code')->unique();
            $table->decimal('total_amount', 12, 2);
            $table->enum('status', ['pending', 'success', 'failed'])->default('pending')->index();
            $table->string('payment_method')->default('transfer');
            $table->string('type')->default('purchase'); // purchase, subscription
            $table->text('notes')->nullable();
            $table->string('payment_proof')->nullable();
            $table->json('items')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
