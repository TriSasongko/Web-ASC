<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Private, Mini Private, Reguler, Mini Reguler, Kompetitif
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->unsignedTinyInteger('max_students')->nullable(); // kapasitas maks per kelas
            $table->unsignedTinyInteger('total_sessions')->nullable(); // 8, 4, atau null utk kompetitif
            $table->decimal('price', 12, 2);
            $table->enum('billing_type', ['per_paket', 'per_bulan'])->default('per_paket');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('programs');
    }
};
