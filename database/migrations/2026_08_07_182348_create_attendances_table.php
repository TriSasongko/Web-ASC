<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')->constrained('classes')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('recorded_by')->constrained('users')->cascadeOnDelete();
            $table->date('attendance_date');
            $table->unsignedTinyInteger('session_number')->default(1);
            $table->enum('status', ['hadir', 'tidak_hadir']);
            $table->timestamps();

            $table->unique(['class_id', 'student_id', 'attendance_date', 'session_number'], 'unique_attendance');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
