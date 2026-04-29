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
        if (!Schema::hasColumn('home_visit_bookings', 'end_time')) {
            Schema::table('home_visit_bookings', function (Blueprint $table) {
                $table->time('end_time')->nullable()->after('time');
                $table->integer('duration_hours')->nullable()->after('end_time');
                $table->decimal('price', 12, 2)->nullable()->after('duration_hours');
                $table->foreignId('transaction_id')->nullable()->constrained()->cascadeOnDelete()->after('price');
                $table->boolean('is_paid')->default(false)->after('transaction_id');
                $table->timestamp('paid_at')->nullable()->after('is_paid');
                $table->timestamp('completed_at')->nullable()->after('status');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('home_visit_bookings', function (Blueprint $table) {
            $table->dropColumn(['end_time', 'duration_hours', 'price', 'transaction_id', 'is_paid', 'paid_at', 'completed_at']);
        });
    }
};
