<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('class_student', function (Blueprint $table) {
            $table->unsignedTinyInteger('level')->nullable()->after('student_id');
        });

        DB::table('class_student')
            ->whereNull('level')
            ->update([
                'level' => DB::raw('(SELECT c.level FROM classes c WHERE c.id = class_student.class_id)'),
            ]);
    }

    public function down(): void
    {
        Schema::table('class_student', function (Blueprint $table) {
            $table->dropColumn('level');
        });
    }
};
