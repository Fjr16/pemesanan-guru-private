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
        Schema::create('tutor_ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->nullable(false);
            $table->foreignId('tutor_id')->nullable(false);
            $table->foreignId('student_id')->nullable(false);
            $table->unsignedInteger('score')->nullable(false)->default(0);
            $table->string('ulasan', 500)->nullable(true);
            $table->timestamp('expired_at')->nullable(true);
            $table->boolean('is_appear')->default(false);
            $table->enum('status', ['pending','rated'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tutor_ratings');
    }
};
