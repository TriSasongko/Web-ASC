<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('class_student', function (Blueprint $table) {
            // Riwayat perpanjangan: satu siswa boleh punya banyak baris untuk kelas yang sama,
            // tapi hanya satu yang aktif. Enforce di level aplikasi (liat RenewalController).
            // MySQL butuh index class_id untuk FK sebelum unique (class_id, student_id) dihapus.
            $table->index('class_id', 'class_student_class_id_index');
            $table->dropUnique('class_student_class_id_student_id_unique');

            $table->enum('renewal_status', [
                'belum_konfirmasi', 'lanjut', 'berhenti', 'pindah',
                'perlu_konfirmasi', 'selesai', 'aktif',
            ])->default('belum_konfirmasi')->change();

            $table->timestamp('started_at')->nullable()->after('renewed_at');
            $table->timestamp('ended_at')->nullable()->after('started_at');
            $table->foreignId('renewed_from_id')->nullable()->after('ended_at')
                ->constrained('class_student')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('class_student', function (Blueprint $table) {
            $table->dropForeign(['renewed_from_id']);
            $table->dropColumn(['started_at', 'ended_at', 'renewed_from_id']);

            $table->enum('renewal_status', ['belum_konfirmasi', 'lanjut', 'berhenti', 'pindah'])
                ->default('belum_konfirmasi')
                ->change();

            $table->unique(['class_id', 'student_id']);
            $table->dropIndex('class_student_class_id_index');
        });
    }
};
