<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('landing_programs')
            ->where('billing_unit', '/sesi')
            ->update(['billing_unit' => '/4 Sesi']);

        DB::table('landing_programs')
            ->where('billing_unit', '/paket')
            ->update(['billing_unit' => '/8 Sesi']);

        DB::table('landing_programs')
            ->where('billing_unit', '/bulan')
            ->update(['billing_unit' => '/Bulan']);

        DB::table('landing_programs')
            ->where('billing_unit', '4 sesi')
            ->update(['billing_unit' => '/4 Sesi']);

        DB::table('landing_programs')
            ->where('billing_unit', '8 sesi')
            ->update(['billing_unit' => '/8 Sesi']);

        DB::table('landing_programs')
            ->where('billing_unit', 'bulan')
            ->update(['billing_unit' => '/Bulan']);
    }

    public function down(): void
    {
        DB::table('landing_programs')
            ->where('billing_unit', '/4 Sesi')
            ->update(['billing_unit' => '/sesi']);

        DB::table('landing_programs')
            ->where('billing_unit', '/8 Sesi')
            ->update(['billing_unit' => '/paket']);

        DB::table('landing_programs')
            ->where('billing_unit', '/Bulan')
            ->update(['billing_unit' => '/bulan']);
    }
};
