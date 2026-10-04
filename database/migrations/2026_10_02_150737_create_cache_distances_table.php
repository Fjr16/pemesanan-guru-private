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
        Schema::create('cache_distances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tutor_id')->nullable(false);
            $table->foreignId('student_id')->nullable(false);
            $table->decimal('distance_m', 10, 2)->nullable(false);
            $table->integer('duration_s')->nullable(false);
            $table->timestamp('computed_at')->nullable(false);
            $table->timestamps();

            $table->index(['tutor_id', 'student_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cache_distances');
    }
};
