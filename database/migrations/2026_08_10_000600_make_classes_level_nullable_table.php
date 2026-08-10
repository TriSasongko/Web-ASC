<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('classes', function (Blueprint $table) {
            $table->unsignedTinyInteger('level')->nullable()->default(null)->change();
        });
    }

    public function down(): void
    {
        Schema::table('classes', function (Blueprint $table) {
            $table->unsignedTinyInteger('level')->nullable(false)->default(1)->change();
        });
    }
};
