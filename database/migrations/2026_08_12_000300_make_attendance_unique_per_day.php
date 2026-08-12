<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropForeign(['student_id']);
            $table->dropUnique('unique_attendance_student');
        });

        Schema::table('attendances', function (Blueprint $table) {
            $table->unique(['student_id', 'attendance_date'], 'unique_attendance_per_day');
            $table->foreign('student_id')->references('id')->on('students')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropForeign(['student_id']);
            $table->dropUnique('unique_attendance_per_day');
        });

        Schema::table('attendances', function (Blueprint $table) {
            $table->unique(['student_id', 'attendance_date', 'session_number'], 'unique_attendance_student');
            $table->foreign('student_id')->references('id')->on('students')->cascadeOnDelete();
        });
    }
};
