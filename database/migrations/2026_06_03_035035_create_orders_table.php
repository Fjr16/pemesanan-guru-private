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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tutor_id')->nullable(false);
            $table->foreignId('student_id')->nullable(false);
            $table->enum('status', ['pending','confirmed','rejected','complete','expired','canceled'])->nullable(false);
            $table->string('catatan')->nullable(true);
            $table->decimal('total_payment')->nullable(true);
            $table->timestamp('expired_at')->nullable(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
