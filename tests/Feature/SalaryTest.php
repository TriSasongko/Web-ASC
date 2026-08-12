<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\ClassStudent;
use App\Models\Program;
use App\Models\SalaryPayment;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\User;
use App\Services\SalaryService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SalaryTest extends TestCase
{
    use RefreshDatabase;

    private function makeAdmin(): User
    {
        return User::factory()->create(['role' => 'admin', 'is_active' => true]);
    }

    private function makeCoach(string $name = 'Coach A'): User
    {
        return User::factory()->create(['role' => 'pelatih', 'name' => $name, 'is_active' => true]);
    }

    private function makeParent(): User
    {
        return User::factory()->create(['role' => 'orang_tua', 'phone' => '081234567800', 'is_active' => true]);
    }

    private function makeStudent(User $parent, string $name): Student
    {
        return Student::create([
            'parent_id' => $parent->id,
            'full_name' => $name,
            'birth_date' => '2015-01-01',
            'gender' => 'L',
        ]);
    }

    private function makeProgram(bool $kompetitif = false): Program
    {
        return Program::create([
            'name' => $kompetitif ? 'Kompetitif' : 'Reguler',
            'slug' => $kompetitif ? 'kompetitif' : 'reguler',
            'total_sessions' => 8,
            'price' => 350000,
            'billing_type' => 'per_paket',
            'is_kompetitif' => $kompetitif,
            'is_active' => true,
        ]);
    }

    private function makeClass(Program $program): SchoolClass
    {
        return SchoolClass::create([
            'program_id' => $program->id,
            'name' => $program->isKompetitif() ? 'Kompetitif A' : 'Reguler A',
            'level' => 1,
            'is_active' => true,
        ]);
    }

    private function recordAttendance(User $coach, SchoolClass $class, array $studentIds, string $date, int $session = 1): void
    {
        foreach ($studentIds as $id) {
            Attendance::create([
                'class_id' => $class->id,
                'student_id' => $id,
                'recorded_by' => $coach->id,
                'attendance_date' => $date,
                'session_number' => $session,
            ]);
        }
    }

    public function test_non_paralel_satu_anak_dihitung_50rb(): void
    {
        $coach = $this->makeCoach();
        $class = $this->makeClass($this->makeProgram());
        $student = $this->makeStudent($this->makeParent(), 'Anak Satu');

        $this->recordAttendance($coach, $class, [$student->id], '2026-08-01');

        $sessions = app(SalaryService::class)->sessionsForCoach($coach);

        $this->assertCount(1, $sessions);
        $this->assertSame(50000, $sessions->first()['nominal']);
        $this->assertFalse($sessions->first()['paralel']);
    }

    public function test_non_paralel_dua_anak_atau_lebih_dihitung_75rb(): void
    {
        $coach = $this->makeCoach();
        $class = $this->makeClass($this->makeProgram());
        $parent = $this->makeParent();
        $s1 = $this->makeStudent($parent, 'Anak Satu');
        $s2 = $this->makeStudent($parent, 'Anak Dua');

        $this->recordAttendance($coach, $class, [$s1->id, $s2->id], '2026-08-01');

        $sessions = app(SalaryService::class)->sessionsForCoach($coach);

        $this->assertCount(1, $sessions);
        $this->assertSame(75000, $sessions->first()['nominal']);
    }

    public function test_paralel_1_plus_1_dihitung_80rb(): void
    {
        $coach = $this->makeCoach();
        $class = $this->makeClass($this->makeProgram());
        $parent = $this->makeParent();
        $s1 = $this->makeStudent($parent, 'Anak Satu');
        $s2 = $this->makeStudent($parent, 'Anak Dua');

        $this->recordAttendance($coach, $class, [$s1->id], '2026-08-01', 1);
        $this->recordAttendance($coach, $class, [$s2->id], '2026-08-01', 2);

        $sessions = app(SalaryService::class)->sessionsForCoach($coach);

        $this->assertCount(1, $sessions);
        $this->assertSame(80000, $sessions->first()['nominal']);
        $this->assertTrue($sessions->first()['paralel']);
        $this->assertSame(2, $sessions->first()['child_count']);
    }

    public function test_paralel_total_tiga_anak_atau_lebih_dihitung_100rb(): void
    {
        $coach = $this->makeCoach();
        $class = $this->makeClass($this->makeProgram());
        $parent = $this->makeParent();
        $s1 = $this->makeStudent($parent, 'Anak Satu');
        $s2 = $this->makeStudent($parent, 'Anak Dua');
        $s3 = $this->makeStudent($parent, 'Anak Tiga');

        $this->recordAttendance($coach, $class, [$s1->id], '2026-08-01', 1);
        $this->recordAttendance($coach, $class, [$s2->id, $s3->id], '2026-08-01', 2);

        $sessions = app(SalaryService::class)->sessionsForCoach($coach);

        $this->assertCount(1, $sessions);
        $this->assertSame(100000, $sessions->first()['nominal']);
    }

    public function test_paralel_2_plus_2_dihitung_100rb(): void
    {
        $coach = $this->makeCoach();
        $class = $this->makeClass($this->makeProgram());
        $parent = $this->makeParent();
        $s1 = $this->makeStudent($parent, 'Anak Satu');
        $s2 = $this->makeStudent($parent, 'Anak Dua');
        $s3 = $this->makeStudent($parent, 'Anak Tiga');
        $s4 = $this->makeStudent($parent, 'Anak Empat');

        $this->recordAttendance($coach, $class, [$s1->id, $s2->id], '2026-08-01', 1);
        $this->recordAttendance($coach, $class, [$s3->id, $s4->id], '2026-08-01', 2);

        $sessions = app(SalaryService::class)->sessionsForCoach($coach);

        $this->assertCount(1, $sessions);
        $this->assertSame(100000, $sessions->first()['nominal']);
    }

    public function test_kelas_paralel_hanya_satu_sesi_tercatat_pakai_tarif_non_paralel(): void
    {
        $coach = $this->makeCoach();
        $class = $this->makeClass($this->makeProgram());
        $parent = $this->makeParent();
        $s1 = $this->makeStudent($parent, 'Anak Satu');
        $s2 = $this->makeStudent($parent, 'Anak Dua');

        $this->recordAttendance($coach, $class, [$s1->id, $s2->id], '2026-08-01', 1);

        $sessions = app(SalaryService::class)->sessionsForCoach($coach);

        $this->assertCount(1, $sessions);
        $this->assertFalse($sessions->first()['paralel']);
        $this->assertSame(75000, $sessions->first()['nominal']);
    }

    public function test_program_kompetitif_tidak_dihitung(): void
    {
        $coach = $this->makeCoach();
        $class = $this->makeClass($this->makeProgram(true));
        $student = $this->makeStudent($this->makeParent(), 'Anak Kompetitif');

        $this->recordAttendance($coach, $class, [$student->id], '2026-08-01');

        $sessions = app(SalaryService::class)->sessionsForCoach($coach);

        $this->assertTrue($sessions->isEmpty());
    }

    public function test_mark_paid_membuka_batch_baru(): void
    {
        $service = app(SalaryService::class);
        $coach = $this->makeCoach();
        $class = $this->makeClass($this->makeProgram());
        $parent = $this->makeParent();
        $s1 = $this->makeStudent($parent, 'Anak Satu');
        $s2 = $this->makeStudent($parent, 'Anak Dua');

        $this->recordAttendance($coach, $class, [$s1->id], '2026-08-01');
        $this->recordAttendance($coach, $class, [$s1->id, $s2->id], '2026-08-08');

        $this->assertTrue($service->markPaid($coach));

        $this->assertSame(125000, SalaryPayment::where('user_id', $coach->id)->value('amount'));
        $this->assertSame(2, SalaryPayment::where('user_id', $coach->id)->value('session_count'));
        $this->assertTrue($service->unpaidSessions($coach)->isEmpty());

        $this->recordAttendance($coach, $class, [$s1->id], '2026-08-15');

        $this->assertCount(1, $service->unpaidSessions($coach));
        $this->assertTrue($service->markPaid($coach));
        $this->assertTrue($service->unpaidSessions($coach)->isEmpty());
        $this->assertFalse($service->markPaid($coach));
    }

    public function test_admin_bisa_mengakses_halaman_honor(): void
    {
        $admin = $this->makeAdmin();
        $coach = $this->makeCoach('Coach Honor');
        $class = $this->makeClass($this->makeProgram());
        $student = $this->makeStudent($this->makeParent(), 'Anak Satu');

        $this->recordAttendance($coach, $class, [$student->id], '2026-08-01');

        $this->actingAs($admin)
            ->get('/admin/salaries')
            ->assertOk()
            ->assertSee('Honor Pelatih')
            ->assertSee('Coach Honor')
            ->assertSee('50.000');
    }

    public function test_admin_dapat_tandai_dibayar(): void
    {
        $admin = $this->makeAdmin();
        $coach = $this->makeCoach();
        $class = $this->makeClass($this->makeProgram());
        $student = $this->makeStudent($this->makeParent(), 'Anak Satu');

        $this->recordAttendance($coach, $class, [$student->id], '2026-08-01');

        $this->actingAs($admin)
            ->post("/admin/salaries/{$coach->id}/pay")
            ->assertRedirect();

        $this->assertSame(1, SalaryPayment::where('user_id', $coach->id)->count());
    }

    public function test_admin_dapat_ubah_nominal_honor(): void
    {
        $admin = $this->makeAdmin();

        $this->actingAs($admin)
            ->put('/admin/salaries/rates', [
                'rate_reguler_satu' => 60000,
                'rate_reguler_dua_plus' => 80000,
                'rate_paralel_dua' => 90000,
                'rate_paralel_banyak' => 120000,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('salary_settings', [
            'rate_reguler_satu' => 60000,
            'rate_paralel_banyak' => 120000,
        ]);
    }

    public function test_absensi_menyimpan_session_number(): void
    {
        $admin = $this->makeAdmin();
        $coach = $this->makeCoach();
        $parent = $this->makeParent();
        $student = $this->makeStudent($parent, 'Anak Satu');
        $class = $this->makeClass($this->makeProgram());

        ClassStudent::create([
            'class_id' => $class->id,
            'student_id' => $student->id,
            'sessions_completed' => 0,
            'is_active' => true,
            'renewal_status' => 'aktif',
        ]);

        $this->actingAs($admin)
            ->post('/admin/attendances', [
                'attendance_date' => '2026-08-01',
                'session_number' => 2,
                'attendance' => [$student->id],
            ])
            ->assertRedirect();

        $this->assertTrue(
            Attendance::where('student_id', $student->id)
                ->whereDate('attendance_date', '2026-08-01')
                ->where('session_number', 2)
                ->exists()
        );
    }
}
