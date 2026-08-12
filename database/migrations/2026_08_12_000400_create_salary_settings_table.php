<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('salary_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('rate_reguler_satu')->default(50000);
            $table->unsignedInteger('rate_reguler_dua_plus')->default(75000);
            $table->unsignedInteger('rate_paralel_dua')->default(80000);
            $table->unsignedInteger('rate_paralel_banyak')->default(100000);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('salary_settings');
    }
};
