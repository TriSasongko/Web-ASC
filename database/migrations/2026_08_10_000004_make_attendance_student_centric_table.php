<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropForeign(['class_id']);
            $table->dropUnique('unique_attendance');

            $table->foreignId('class_id')->nullable()->change();

            $table->unique(['student_id', 'attendance_date', 'session_number'], 'unique_attendance_student');
            $table->foreign('class_id')->references('id')->on('classes')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropForeign(['class_id']);
            $table->dropUnique('unique_attendance_student');

            $table->foreignId('class_id')->nullable(false)->change();

            $table->unique(['class_id', 'student_id', 'attendance_date', 'session_number'], 'unique_attendance');
            $table->foreign('class_id')->references('id')->on('classes')->cascadeOnDelete();
        });
    }
};
