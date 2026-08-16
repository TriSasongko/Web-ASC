<?php

namespace Tests\Feature;

use App\Models\ClassSchedule;
use App\Models\Program;
use App\Models\SchoolClass;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PelatihDashboardTest extends TestCase
{
    use RefreshDatabase;

    private function makeCoach(): User
    {
        return User::factory()->create(['role' => 'pelatih', 'is_active' => true]);
    }

    public function test_dashboard_shows_todays_schedule_for_coach()
    {
        $coach = $this->makeCoach();

        $program = Program::create([
            'name' => 'Reguler',
            'slug' => 'reguler',
            'total_sessions' => 8,
            'price' => 350000,
            'billing_type' => 'per_paket',
            'is_active' => true,
        ]);

        $class = SchoolClass::create([
            'program_id' => $program->id,
            'name' => 'Reguler A',
            'level' => 1,
            'is_active' => true,
        ]);

        $todayDay = ClassSchedule::DAYS[(now()->dayOfWeek + 6) % 7];

        $schedule = ClassSchedule::create([
            'class_id' => $class->id,
            'day' => $todayDay,
            'start_time' => '08:00',
            'end_time' => '09:00',
            'location' => 'Kolam Utama',
            'session_number' => 1,
        ]);

        $schedule->coaches()->attach($coach->id);

        $this->actingAs($coach)
            ->get(route('pelatih.dashboard'))
            ->assertOk()
            ->assertSee('Jadwal Hari Ini')
            ->assertSee('Reguler A');
    }

    public function test_dashboard_shows_empty_state_when_no_schedule_today()
    {
        $coach = $this->makeCoach();

        $this->actingAs($coach)
            ->get(route('pelatih.dashboard'))
            ->assertOk()
            ->assertSee('Tidak ada jadwal latihan hari ini.');
    }
}
