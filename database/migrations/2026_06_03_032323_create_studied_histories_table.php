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
        Schema::create('studied_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tutor_id')->nullable(false);
            $table->string('sekolah',50)->nullable(false);
            $table->string('jurusan',50)->nullable(false);
            $table->string('periode',50)->nullable(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('studied_histories');
    }
};
