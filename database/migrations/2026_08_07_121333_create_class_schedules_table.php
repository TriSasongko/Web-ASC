<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('class_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')->constrained('classes')->cascadeOnDelete();
            $table->enum('day', ['senin','selasa','rabu','kamis','jumat','sabtu','minggu']);
            $table->time('start_time');
            $table->time('end_time');
            $table->string('location')->nullable();
            $table->unsignedTinyInteger('session_number')->default(1); // untuk kelas paralel: sesi 1 / sesi 2
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('class_schedules');
    }
};
