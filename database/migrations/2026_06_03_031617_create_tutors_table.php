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
        Schema::create('tutors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable(false);
            $table->string('name', 50)->nullable(false);
            $table->enum('jenis_kelamin', ['Pria', 'Wanita'])->nullable(false);
            $table->date('tanggal_lhr')->nullable(false);
            $table->string('foto')->nullable(true);
            $table->string('desc', 1000)->nullable(true);
            $table->string('job',30)->nullable(false);
            $table->decimal('hourly_rate')->nullable(false);
            $table->enum('lokasi_mengajar', ['offline', 'online', 'fleksibel'])->default('offline');
            $table->string('domisili',500)->nullable(false);
            $table->decimal('latitude',10,7)->nullable(false);
            $table->decimal('langitude',10,7)->nullable(false);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tutors');
    }
};
