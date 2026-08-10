<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('programs', function (Blueprint $table) {
            $table->boolean('is_kompetitif')->default(false)->after('billing_type');
        });

        DB::table('programs')
            ->where('slug', 'kompetitif')
            ->update(['is_kompetitif' => true]);
    }

    public function down(): void
    {
        Schema::table('programs', function (Blueprint $table) {
            $table->dropColumn('is_kompetitif');
        });
    }
};
