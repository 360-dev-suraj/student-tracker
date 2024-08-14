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
        Schema::table('student_availability', function (Blueprint $table) {
            $table->year('year')->after('student_id')->nullable();
            $table->integer('month')->after('year')->nullable();
            $table->integer('week')->after('month')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_availability', function (Blueprint $table) {
            $table->dropColumn(['year', 'month', 'week']);
        });
    }
};
