<?php

namespace Tests\Feature;

use App\Models\ClassStudent;
use App\Models\Program;
use App\Models\Registration;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminStudentIndexFilterTest extends TestCase
{
    use RefreshDatabase;

    private function makeAdmin(): User
    {
        return User::factory()->create(['role' => 'admin', 'is_active' => true]);
    }

    private function createStudent(string $name, string $renewalStatus, bool $isActive = true): Student
    {
        $parent = User::factory()->create(['role' => 'orang_tua', 'is_active' => true]);

        $student = Student::create([
            'parent_id' => $parent->id,
            'full_name' => $name,
            'birth_date' => '2015-01-01',
            'gender' => 'L',
        ]);

        $program = Program::firstOrCreate(['slug' => 'reguler'], [
            'name' => 'Reguler',
            'total_sessions' => 8,
            'price' => 350000,
            'billing_type' => 'per_paket',
            'is_active' => true,
        ]);

        $class = SchoolClass::create([
            'program_id' => $program->id,
            'name' => 'Reguler A',
            'level' => 1,
            'capacity' => 10,
            'is_active' => true,
        ]);

        Registration::create([
            'student_id' => $student->id,
            'program_id' => $program->id,
            'status' => 'diterima',
        ]);

        ClassStudent::create([
            'class_id' => $class->id,
            'student_id' => $student->id,
            'sessions_completed' => 4,
            'is_active' => $isActive,
            'renewal_status' => $renewalStatus,
        ]);

        return $student;
    }

    public function test_index_defaults_to_active_enrollments(): void
    {
        $admin = $this->makeAdmin();
        $active = $this->createStudent('Anak Aktif', 'aktif');
        $stopped = $this->createStudent('Anak Berhenti', 'berhenti', false);

        $this->actingAs($admin)
            ->get(route('admin.students.index'))
            ->assertOk()
            ->assertSee($active->full_name)
            ->assertDontSee($stopped->full_name);
    }

    public function test_index_semua_lists_all_students(): void
    {
        $admin = $this->makeAdmin();
        $active = $this->createStudent('Anak Aktif', 'aktif');
        $stopped = $this->createStudent('Anak Berhenti', 'berhenti', false);

        $this->actingAs($admin)
            ->get(route('admin.students.index', ['status' => 'semua']))
            ->assertOk()
            ->assertSee($active->full_name)
            ->assertSee($stopped->full_name);
    }

    public function test_index_filters_perlu_konfirmasi(): void
    {
        $admin = $this->makeAdmin();
        $pending = $this->createStudent('Anak Perlu', 'perlu_konfirmasi');
        $active = $this->createStudent('Anak Aktif', 'aktif');

        $this->actingAs($admin)
            ->get(route('admin.students.index', ['status' => 'perlu_konfirmasi']))
            ->assertOk()
            ->assertSee($pending->full_name)
            ->assertDontSee($active->full_name);
    }

    public function test_index_filters_berhenti_shows_badge(): void
    {
        $admin = $this->makeAdmin();
        $stopped = $this->createStudent('Anak Berhenti', 'berhenti', false);
        $active = $this->createStudent('Anak Aktif', 'aktif');

        $this->actingAs($admin)
            ->get(route('admin.students.index', ['status' => 'berhenti']))
            ->assertOk()
            ->assertSee($stopped->full_name)
            ->assertSee('Berhenti')
            ->assertDontSee($active->full_name);
    }

    public function test_index_filters_pindah_shows_badge(): void
    {
        $admin = $this->makeAdmin();
        $moved = $this->createStudent('Anak Pindah', 'pindah', false);

        $this->actingAs($admin)
            ->get(route('admin.students.index', ['status' => 'pindah']))
            ->assertOk()
            ->assertSee($moved->full_name)
            ->assertSee('Pindah');
    }

    public function test_index_invalid_status_falls_back_to_aktif(): void
    {
        $admin = $this->makeAdmin();
        $active = $this->createStudent('Anak Aktif', 'aktif');
        $stopped = $this->createStudent('Anak Berhenti', 'berhenti', false);

        $this->actingAs($admin)
            ->get(route('admin.students.index', ['status' => 'bogus']))
            ->assertOk()
            ->assertSee($active->full_name)
            ->assertDontSee($stopped->full_name);
    }

    public function test_index_combines_search_and_status(): void
    {
        $admin = $this->makeAdmin();
        $stopped = $this->createStudent('Anak Berhenti', 'berhenti', false);
        $otherStopped = $this->createStudent('Anak Lain', 'berhenti', false);

        $this->actingAs($admin)
            ->get(route('admin.students.index', ['status' => 'berhenti', 'search' => 'Berhenti']))
            ->assertOk()
            ->assertSee($stopped->full_name)
            ->assertDontSee($otherStopped->full_name);
    }
}
