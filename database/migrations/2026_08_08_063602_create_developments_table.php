<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('developments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')->constrained('classes')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('coach_id')->constrained('users')->cascadeOnDelete();
            $table->string('period'); // contoh: "Agustus 2026" atau "Paket 1"

            $table->enum('adaptasi_air', ['belum', 'cukup', 'baik', 'sangat_baik'])->default('belum');
            $table->enum('mengapung', ['belum', 'cukup', 'baik', 'sangat_baik'])->default('belum');
            $table->enum('gerakan_kaki', ['belum', 'cukup', 'baik', 'sangat_baik'])->default('belum');
            $table->enum('gerakan_tangan', ['belum', 'cukup', 'baik', 'sangat_baik'])->default('belum');
            $table->enum('pernapasan', ['belum', 'cukup', 'baik', 'sangat_baik'])->default('belum');
            $table->enum('gaya_bebas', ['belum', 'cukup', 'baik', 'sangat_baik'])->default('belum');
            $table->enum('gaya_dada', ['belum', 'cukup', 'baik', 'sangat_baik'])->default('belum');
            $table->enum('gaya_punggung', ['belum', 'cukup', 'baik', 'sangat_baik'])->default('belum');
            $table->enum('gaya_kupu_kupu', ['belum', 'cukup', 'baik', 'sangat_baik'])->default('belum');

            $table->text('coach_note')->nullable();
            $table->timestamps();

            $table->unique(['class_id', 'student_id', 'period']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('developments');
    }
};
