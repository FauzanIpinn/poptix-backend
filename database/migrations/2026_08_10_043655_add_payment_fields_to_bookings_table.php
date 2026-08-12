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
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('snap_token')->nullable()->after('status');
            $table->string('payment_type')->nullable()->after('snap_token');
            $table->string('midtrans_order_id')->unique()->nullable()->after('payment_type');
            $table->timestamp('paid_at')->nullable()->after('midtrans_order_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['snap_token', 'payment_type', 'midtrans_order_id', 'paid_at']);
        });
    }
};
