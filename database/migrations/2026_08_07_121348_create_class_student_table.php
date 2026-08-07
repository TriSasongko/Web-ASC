<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('class_student', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')->constrained('classes')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('registration_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedTinyInteger('sessions_completed')->default(0); // rekap pertemuan: x/8, x/4
            $table->boolean('is_active')->default(true); // sudah lulus paket / masih aktif
            $table->timestamps();

            $table->unique(['class_id', 'student_id']); // siswa tidak dobel di kelas yang sama
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('class_student');
    }
};
