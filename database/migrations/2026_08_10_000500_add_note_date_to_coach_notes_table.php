<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('coach_notes', function (Blueprint $table) {
            $table->date('note_date')->nullable()->after('content');
        });

        DB::table('coach_notes')->whereNull('note_date')->update([
            'note_date' => DB::raw('DATE(created_at)'),
        ]);
    }

    public function down(): void
    {
        Schema::table('coach_notes', function (Blueprint $table) {
            $table->dropColumn('note_date');
        });
    }
};
