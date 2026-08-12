<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\ClassStudent;
use App\Models\Program;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AttendanceDayUniqueTest extends TestCase
{
    use RefreshDatabase;

    private function makeAdmin(): User
    {
        return User::factory()->create(['role' => 'admin', 'is_active' => true]);
    }

    private function makeCoach(): User
    {
        return User::factory()->create(['role' => 'pelatih', 'is_active' => true]);
    }

    private function makeParent(): User
    {
        return User::factory()->create(['role' => 'orang_tua', 'phone' => '081234567800', 'is_active' => true]);
    }

    private function makeStudent(User $parent): Student
    {
        return Student::create([
            'parent_id' => $parent->id,
            'full_name' => 'Anak ASC',
            'birth_date' => '2015-01-01',
            'gender' => 'L',
        ]);
    }

    private function makeProgram(): Program
    {
        return Program::create([
            'name' => 'Reguler',
            'slug' => 'reguler',
            'total_sessions' => 8,
            'price' => 350000,
            'billing_type' => 'per_paket',
            'is_active' => true,
        ]);
    }

    private function makeClass(Program $program): SchoolClass
    {
        return SchoolClass::create([
            'program_id' => $program->id,
            'name' => 'Reguler A',
            'level' => 1,
            'is_active' => true,
        ]);
    }

    private function makeEnrollment(Student $student, SchoolClass $class): ClassStudent
    {
        return ClassStudent::create([
            'class_id' => $class->id,
            'student_id' => $student->id,
            'sessions_completed' => 0,
            'is_active' => true,
            'renewal_status' => 'aktif',
        ]);
    }

    public function test_admin_cannot_record_same_student_twice_on_same_day(): void
    {
        $admin = $this->makeAdmin();
        $class = $this->makeClass($this->makeProgram());
        $student = $this->makeStudent($this->makeParent());
        $this->makeEnrollment($student, $class);

        $payload = ['attendance_date' => '2026-08-12', 'attendance' => [$student->id]];

        $this->actingAs($admin)->post(route('admin.attendances.store'), $payload)->assertRedirect();
        $this->actingAs($admin)->post(route('admin.attendances.store'), $payload)
            ->assertSessionHasErrors('attendance');

        $this->assertSame(1, Attendance::where('student_id', $student->id)->count());
    }

    public function test_admin_cannot_record_student_already_recorded_by_coach_same_day(): void
    {
        $admin = $this->makeAdmin();
        $coach = $this->makeCoach();
        $class = $this->makeClass($this->makeProgram());
        $student = $this->makeStudent($this->makeParent());
        $this->makeEnrollment($student, $class);

        $payload = ['attendance_date' => '2026-08-12', 'attendance' => [$student->id]];

        $this->actingAs($coach)->post(route('pelatih.attendances.store'), $payload)->assertRedirect();
        $this->actingAs($admin)->post(route('admin.attendances.store'), $payload)
            ->assertSessionHasErrors('attendance');

        $this->assertSame(1, Attendance::where('student_id', $student->id)->count());
    }

    public function test_coach_cannot_record_student_already_recorded_by_another_coach_same_day(): void
    {
        $coachA = $this->makeCoach();
        $coachB = $this->makeCoach();
        $class = $this->makeClass($this->makeProgram());
        $student = $this->makeStudent($this->makeParent());
        $this->makeEnrollment($student, $class);

        $payload = ['attendance_date' => '2026-08-12', 'attendance' => [$student->id]];

        $this->actingAs($coachA)->post(route('pelatih.attendances.store'), $payload)->assertRedirect();
        $this->actingAs($coachB)->post(route('pelatih.attendances.store'), $payload)
            ->assertSessionHasErrors('attendance');

        $this->assertSame(1, Attendance::where('student_id', $student->id)->count());
    }

    public function test_same_student_can_be_recorded_on_different_days(): void
    {
        $admin = $this->makeAdmin();
        $class = $this->makeClass($this->makeProgram());
        $student = $this->makeStudent($this->makeParent());
        $this->makeEnrollment($student, $class);

        $this->actingAs($admin)->post(route('admin.attendances.store'), [
            'attendance_date' => '2026-08-12',
            'attendance' => [$student->id],
        ])->assertRedirect();

        $this->actingAs($admin)->post(route('admin.attendances.store'), [
            'attendance_date' => '2026-08-13',
            'attendance' => [$student->id],
        ])->assertRedirect();

        $this->assertSame(2, Attendance::where('student_id', $student->id)->count());
    }

    public function test_different_students_can_be_recorded_on_same_day(): void
    {
        $admin = $this->makeAdmin();
        $class = $this->makeClass($this->makeProgram());
        $studentA = $this->makeStudent($this->makeParent());
        $studentB = $this->makeStudent($this->makeParent());
        $this->makeEnrollment($studentA, $class);
        $this->makeEnrollment($studentB, $class);

        $this->actingAs($admin)->post(route('admin.attendances.store'), [
            'attendance_date' => '2026-08-12',
            'attendance' => [$studentA->id, $studentB->id],
        ])->assertRedirect();

        $this->assertSame(2, Attendance::whereDate('attendance_date', '2026-08-12')->count());
    }

    public function test_create_page_shows_already_recorded_students_as_absent_done(): void
    {
        $admin = $this->makeAdmin();
        $class = $this->makeClass($this->makeProgram());
        $student = $this->makeStudent($this->makeParent());
        $this->makeEnrollment($student, $class);

        $this->actingAs($admin)->post(route('admin.attendances.store'), [
            'attendance_date' => '2026-08-12',
            'attendance' => [$student->id],
        ])->assertRedirect();

        $this->actingAs($admin)
            ->get(route('admin.attendances.create'))
            ->assertOk()
            ->assertSee('Sudah di absen')
            ->assertSee((string) $student->id, false);
    }

    public function test_coach_create_page_renders_and_marks_recorded_students(): void
    {
        $coach = $this->makeCoach();
        $class = $this->makeClass($this->makeProgram());
        $student = $this->makeStudent($this->makeParent());
        $this->makeEnrollment($student, $class);

        $this->actingAs($coach)->post(route('pelatih.attendances.store'), [
            'attendance_date' => '2026-08-12',
            'attendance' => [$student->id],
        ])->assertRedirect();

        $this->actingAs($coach)
            ->get(route('pelatih.attendances.create'))
            ->assertOk()
            ->assertSee('Sudah di absen');
    }

    public function test_db_unique_index_rejects_duplicate_insert_for_same_student_same_day(): void
    {
        $class = $this->makeClass($this->makeProgram());
        $student = $this->makeStudent($this->makeParent());
        $this->makeEnrollment($student, $class);

        Attendance::create([
            'class_id' => $class->id,
            'class_student_id' => null,
            'student_id' => $student->id,
            'recorded_by' => $this->makeAdmin()->id,
            'attendance_date' => '2026-08-12',
            'session_number' => 1,
        ]);

        $this->expectException(QueryException::class);

        Attendance::create([
            'class_id' => $class->id,
            'class_student_id' => null,
            'student_id' => $student->id,
            'recorded_by' => $this->makeAdmin()->id,
            'attendance_date' => '2026-08-12',
            'session_number' => 1,
        ]);
    }
}
