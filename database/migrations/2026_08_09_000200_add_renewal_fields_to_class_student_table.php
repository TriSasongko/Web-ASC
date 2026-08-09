<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('class_student', function (Blueprint $table) {
            $table->enum('renewal_status', ['belum_konfirmasi', 'lanjut', 'berhenti'])->default('belum_konfirmasi')->after('is_active');
            $table->text('renewal_note')->nullable()->after('renewal_status');
            $table->timestamp('renewed_at')->nullable()->after('renewal_note');
        });
    }

    public function down(): void
    {
        Schema::table('class_student', function (Blueprint $table) {
            $table->dropColumn(['renewal_status', 'renewal_note', 'renewed_at']);
        });
    }
};
