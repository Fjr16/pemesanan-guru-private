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
        Schema::create('schedule_locks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tutor_schedule_id')->nullable(false);
            $table->foreignId('order_detail_id')->nullable(false);
            $table->date('tanggal')->nullable(false);
            $table->enum('status', ['locked', 'confirmed', 'release'])->default('locked');
            $table->timestamp('locked_at')->nullable(true);
            $table->timestamp('expired_at')->nullable(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedule_locks');
    }
};
