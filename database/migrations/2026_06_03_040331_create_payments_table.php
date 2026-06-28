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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->nullable(false);
            $table->string('transactionId', 50)->nullable(true)->unique(); // transaction order id for midtrans
            $table->string('payment_token')->nullable(true)->unique();
            $table->string('metode')->nullable(true);
            $table->decimal('amount')->nullable(true);
            $table->enum('status', ['pending', 'paid', 'failed', 'expired', 'refunded'])->default('pending');
            $table->timestamp('expired_at')->nullable(true);
            $table->timestamp('paid_at')->nullable(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
