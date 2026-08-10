<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('class_schedule_student', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_schedule_id')->constrained('class_schedules')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->unique(['class_schedule_id', 'student_id']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('class_schedule_student');
    }
};
