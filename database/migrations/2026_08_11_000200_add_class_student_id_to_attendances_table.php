<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->foreignId('class_student_id')->nullable()->after('class_id')
                ->constrained('class_student')->nullOnDelete();
        });

        // Backfill: arahkan absen lama ke pivot periode paket yang aktif (atau terbaru).
        DB::table('attendances')
            ->whereNull('class_student_id')
            ->whereNotNull('class_id')
            ->update([
                'class_student_id' => DB::raw('(
                    SELECT cs.id FROM class_student cs
                    WHERE cs.student_id = attendances.student_id
                      AND cs.class_id = attendances.class_id
                    ORDER BY cs.is_active DESC, cs.id DESC
                    LIMIT 1
                )'),
            ]);
    }

    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropConstrainedForeignId('class_student_id');
        });
    }
};
