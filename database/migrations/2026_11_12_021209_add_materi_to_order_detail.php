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
        Schema::table('order_details', function (Blueprint $table) {
            $table->enum('status', ['pending','completed'])->default('pending');
            $table->string('tujuan_pembelajaran', 500)->nullable(true);
            $table->string('materi', 255)->nullable(true);
            $table->string('penilaian', 500)->nullable(true);
            $table->string('kehadiran', 20)->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_details', function (Blueprint $table) {
            $table->dropColumn('tujuan_pembelajaran');
            $table->dropColumn('materi');
            $table->dropColumn('penilaian');
            $table->dropColumn('kehadiran');
            $table->dropColumn('status');
        });
    }
};
