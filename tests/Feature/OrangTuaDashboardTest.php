<?php

namespace Tests\Feature;

use App\Models\ClassSchedule;
use App\Models\ClassStudent;
use App\Models\Development;
use App\Models\Program;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrangTuaDashboardTest extends TestCase
{
    use RefreshDatabase;

    private function makeParent(): User
    {
        return User::factory()->create(['role' => 'orang_tua', 'is_active' => true]);
    }

    public function test_dashboard_shows_todays_schedule_for_children()
    {
        $parent = $this->makeParent();

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

        $student = Student::create([
            'parent_id' => $parent->id,
            'full_name' => 'Anak Satu',
            'gender' => 'L',
        ]);

        ClassStudent::create([
            'class_id' => $class->id,
            'student_id' => $student->id,
            'level' => 1,
            'sessions_completed' => 3,
            'is_active' => true,
            'renewal_status' => 'aktif',
            'started_at' => now(),
        ]);

        $todayDay = ClassSchedule::DAYS[now()->dayOfWeek - 1];

        ClassSchedule::create([
            'class_id' => $class->id,
            'day' => $todayDay,
            'start_time' => '08:00',
            'end_time' => '09:00',
            'location' => 'Kolam Utama',
            'session_number' => 1,
        ]);

        $this->actingAs($parent)
            ->get(route('orangtua.dashboard'))
            ->assertOk()
            ->assertSee('Jadwal Latihan Hari Ini')
            ->assertSee('Reguler A')
            ->assertSee('Sisa 5x');
    }

    public function test_dashboard_shows_empty_state_when_no_children()
    {
        $parent = $this->makeParent();

        $this->actingAs($parent)
            ->get(route('orangtua.dashboard'))
            ->assertOk()
            ->assertSee('Daftarkan anak sekarang');
    }

    public function test_dashboard_shows_development_pie_chart_per_child()
    {
        $parent = $this->makeParent();
        $coach = User::factory()->create(['role' => 'pelatih', 'is_active' => true]);

        $program = Program::create([
            'name' => 'Kompetitif',
            'slug' => 'kompetitif',
            'total_sessions' => null,
            'price' => 500000,
            'billing_type' => 'per_bulan',
            'is_active' => true,
        ]);

        $class = SchoolClass::create([
            'program_id' => $program->id,
            'name' => 'Kompetitif A',
            'level' => 1,
            'is_active' => true,
        ]);

        $withDev = Student::create([
            'parent_id' => $parent->id,
            'full_name' => 'Anak Dinilai',
            'gender' => 'L',
        ]);

        $withoutDev = Student::create([
            'parent_id' => $parent->id,
            'full_name' => 'Anak Belum Dinilai',
            'gender' => 'P',
        ]);

        Development::create([
            'class_id' => $class->id,
            'student_id' => $withDev->id,
            'coach_id' => $coach->id,
            'period' => 'Agustus 2026',
            'adaptasi_lingkungan_baru' => 'baik',
            'komunikasi' => 'baik',
            'menerima_instruksi' => 'sangat_baik',
            'disiplin' => 'sangat_baik',
            'percaya_diri' => 'baik',
            'daya_tahan' => 'baik',
            'recovery' => 'cukup',
            'water_survive' => 'baik',
        ]);

        $this->actingAs($parent)
            ->get(route('orangtua.dashboard'))
            ->assertOk()
            ->assertSee('Perkembangan Penilaian Umum')
            ->assertSee('Anak Dinilai')
            ->assertSee('Agustus 2026')
            ->assertSee('8 aspek dinilai')
            ->assertSee('Detail E-Raport')
            ->assertSee('Anak Belum Dinilai')
            ->assertSee('Belum ada penilaian');
    }
}
