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
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->nullable(false);
            $table->foreignId('tutor_schedule_id')->nullable(false);
            $table->date('tanggal')->nullable(false);
            $table->time('jam_start')->nullable(false);
            $table->time('jam_end')->nullable(false);
            $table->decimal('harga')->nullable(false);
            $table->enum('status', ['pending','confirmed','rejected'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_details');
    }
};
