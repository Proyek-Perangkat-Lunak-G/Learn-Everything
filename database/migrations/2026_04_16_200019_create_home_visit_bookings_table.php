<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('home_visit_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tutor_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->time('time');
            $table->time('end_time')->nullable();
            $table->integer('duration_hours')->nullable();
            $table->decimal('price', 12, 2)->nullable();
            $table->foreignId('transaction_id')->nullable()->constrained()->cascadeOnDelete();
            $table->boolean('is_paid')->default(false);
            $table->timestamp('paid_at')->nullable();
            $table->text('location');
            $table->enum('status', ['pending', 'confirmed', 'completed', 'cancelled'])->default('pending')->index();
            $table->timestamp('completed_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('home_visit_bookings');
    }
};
