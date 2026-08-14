<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('best_times', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('class_id')->constrained('classes')->cascadeOnDelete();
            $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('style', ['bebas', 'dada', 'punggung', 'kupu_kupu']);
            $table->smallInteger('distance');
            $table->unsignedInteger('time_ms');
            $table->date('recorded_at');
            $table->timestamps();

            $table->index(['student_id', 'style', 'distance']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('best_times');
    }
};
