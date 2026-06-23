<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tutors', function (Blueprint $table) {
            $table->enum('status', ['pending', 'active', 'rejected'])->default('pending')->after('lokasi_mengajar');
        });
    }

    public function down(): void
    {
        Schema::table('tutors', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
