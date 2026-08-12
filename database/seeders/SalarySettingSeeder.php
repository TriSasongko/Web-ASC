<?php

namespace Database\Seeders;

use App\Models\CoachSalarySetting;
use App\Models\SalarySetting;
use App\Models\User;
use Illuminate\Database\Seeder;

class SalarySettingSeeder extends Seeder
{
    public function run(): void
    {
        SalarySetting::updateOrCreate(
            [],
            [
                'rate_reguler_satu' => 50000,
                'rate_reguler_dua_plus' => 75000,
                'rate_paralel_dua' => 80000,
                'rate_paralel_banyak' => 100000,
            ]
        );

        $coachIds = User::where('role', 'pelatih')->pluck('id');

        foreach ($coachIds as $coachId) {
            CoachSalarySetting::firstOrCreate(
                ['user_id' => $coachId],
                ['session_limit' => 8]
            );
        }
    }
}
