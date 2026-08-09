<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('class_recommendations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('from_user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('current_class_id')->nullable()->constrained('classes')->nullOnDelete();
            $table->foreignId('recommended_class_id')->nullable()->constrained('classes')->nullOnDelete();
            $table->unsignedTinyInteger('recommended_level')->nullable();
            $table->text('note')->nullable();
            $table->enum('status', ['pending', 'diterima', 'ditolak'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('class_recommendations');
    }
};
