<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('best_times', function (Blueprint $table) {
            $table->enum('style', ['bebas', 'dada', 'punggung', 'kupu_kupu', 'im'])->change();
        });
    }

    public function down(): void
    {
        Schema::table('best_times', function (Blueprint $table) {
            $table->enum('style', ['bebas', 'dada', 'punggung', 'kupu_kupu'])->change();
        });
    }
};
