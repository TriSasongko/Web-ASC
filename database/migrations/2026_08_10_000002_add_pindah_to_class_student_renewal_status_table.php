<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('class_student', function (Blueprint $table) {
            $table->enum('renewal_status', ['belum_konfirmasi', 'lanjut', 'berhenti', 'pindah'])
                ->default('belum_konfirmasi')
                ->change();
        });
    }

    public function down(): void
    {
        Schema::table('class_student', function (Blueprint $table) {
            $table->enum('renewal_status', ['belum_konfirmasi', 'lanjut', 'berhenti'])
                ->default('belum_konfirmasi')
                ->change();
        });
    }
};
