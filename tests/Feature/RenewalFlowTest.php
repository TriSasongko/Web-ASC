<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\ClassStudent;
use App\Models\Program;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RenewalFlowTest extends TestCase
{
    use RefreshDatabase;

    private function makeAdmin(): User
    {
        return User::factory()->create(['role' => 'admin', 'is_active' => true]);
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
            'capacity' => 10,
            'is_active' => true,
        ]);
    }

    private function makeEnrollment(Student $student, SchoolClass $class, int $sessionsCompleted, string $renewalStatus): ClassStudent
    {
        return ClassStudent::create([
            'class_id' => $class->id,
            'student_id' => $student->id,
            'sessions_completed' => $sessionsCompleted,
            'is_active' => true,
            'renewal_status' => $renewalStatus,
        ]);
    }

    public function test_renewal_check_flags_enrollment_when_sessions_reach_threshold(): void
    {
        $class = $this->makeClass($this->makeProgram());
        $enrollment = $this->makeEnrollment($this->makeStudent($this->makeParent()), $class, 7, 'belum_konfirmasi');

        $this->artisan('renewal:check')->assertExitCode(0);

        $this->assertSame('perlu_konfirmasi', $enrollment->fresh()->renewal_status);
        $this->assertSame(1, ClassStudent::where('student_id', $enrollment->student_id)->count());
    }

    public function test_renewal_check_flags_enrollment_when_sessions_exactly_finished(): void
    {
        $class = $this->makeClass($this->makeProgram());
        $enrollment = $this->makeEnrollment($this->makeStudent($this->makeParent()), $class, 8, 'aktif');

        $this->artisan('renewal:check')->assertExitCode(0);

        $this->assertSame('perlu_konfirmasi', $enrollment->fresh()->renewal_status);
    }

    public function test_renewal_check_does_not_flag_enrollment_far_from_threshold(): void
    {
        $class = $this->makeClass($this->makeProgram());
        $enrollment = $this->makeEnrollment($this->makeStudent($this->makeParent()), $class, 3, 'belum_konfirmasi');

        $this->artisan('renewal:check')->assertExitCode(0);

        $this->assertSame('belum_konfirmasi', $enrollment->fresh()->renewal_status);
    }

    public function test_renewal_check_is_idempotent_for_existing_perlu_konfirmasi(): void
    {
        $class = $this->makeClass($this->makeProgram());
        $enrollment = $this->makeEnrollment($this->makeStudent($this->makeParent()), $class, 8, 'perlu_konfirmasi');

        $this->artisan('renewal:check')->assertExitCode(0);

        $this->assertSame('perlu_konfirmasi', $enrollment->fresh()->renewal_status);
        $this->assertSame(1, ClassStudent::where('student_id', $enrollment->student_id)->count());
    }

    public function test_renewal_check_skips_inactive_and_stopped_enrollments(): void
    {
        $class = $this->makeClass($this->makeProgram());

        $active = $this->makeEnrollment($this->makeStudent($this->makeParent()), $class, 8, 'aktif');
        $stopped = $this->makeEnrollment($this->makeStudent($this->makeParent()), $class, 8, 'berhenti');
        $stopped->update(['is_active' => false]);

        $this->artisan('renewal:check')->assertExitCode(0);

        $this->assertSame('perlu_konfirmasi', $active->fresh()->renewal_status);
        $this->assertSame('berhenti', $stopped->fresh()->renewal_status);
    }

    public function test_admin_can_confirm_renewal_creates_new_period_pivot(): void
    {
        $admin = $this->makeAdmin();
        $student = $this->makeStudent($this->makeParent());
        $class = $this->makeClass($this->makeProgram());
        $enrollment = $this->makeEnrollment($student, $class, 8, 'perlu_konfirmasi');

        $this->actingAs($admin)
            ->post(route('admin.renewals.confirm', [$student, $enrollment]))
            ->assertRedirect()
            ->assertSessionHas('success');

        $old = $enrollment->fresh();
        $this->assertFalse($old->is_active);
        $this->assertSame('selesai', $old->renewal_status);
        $this->assertNotNull($old->ended_at);

        $new = ClassStudent::where('student_id', $student->id)->where('id', '!=', $old->id)->first();
        $this->assertNotNull($new);
        $this->assertSame($class->id, $new->class_id);
        $this->assertSame(0, $new->sessions_completed);
        $this->assertTrue($new->is_active);
        $this->assertSame('aktif', $new->renewal_status);
        $this->assertNotNull($new->started_at);
        $this->assertNull($new->ended_at);
        $this->assertSame($old->id, $new->renewed_from_id);
    }

    public function test_admin_can_decline_renewal_deactivates_pivot(): void
    {
        $admin = $this->makeAdmin();
        $student = $this->makeStudent($this->makeParent());
        $class = $this->makeClass($this->makeProgram());
        $enrollment = $this->makeEnrollment($student, $class, 8, 'perlu_konfirmasi');

        $this->actingAs($admin)
            ->post(route('admin.renewals.decline', [$student, $enrollment]))
            ->assertRedirect()
            ->assertSessionHas('success');

        $fresh = $enrollment->fresh();
        $this->assertFalse($fresh->is_active);
        $this->assertSame('berhenti', $fresh->renewal_status);
        $this->assertNotNull($fresh->ended_at);
        $this->assertSame(1, ClassStudent::where('student_id', $student->id)->count());
    }

    public function test_confirm_renewal_requires_perlu_konfirmasi_status(): void
    {
        $admin = $this->makeAdmin();
        $student = $this->makeStudent($this->makeParent());
        $class = $this->makeClass($this->makeProgram());
        $enrollment = $this->makeEnrollment($student, $class, 8, 'belum_konfirmasi');

        $this->actingAs($admin)
            ->post(route('admin.renewals.confirm', [$student, $enrollment]))
            ->assertForbidden();

        $this->assertTrue($enrollment->fresh()->is_active);
        $this->assertSame(1, ClassStudent::where('student_id', $student->id)->count());
    }

    public function test_non_admin_cannot_access_renewal_pages(): void
    {
        $coach = User::factory()->create(['role' => 'pelatih', 'is_active' => true]);
        $student = $this->makeStudent($this->makeParent());
        $class = $this->makeClass($this->makeProgram());
        $enrollment = $this->makeEnrollment($student, $class, 8, 'perlu_konfirmasi');

        $this->actingAs($coach)->get(route('admin.renewals.index'))->assertForbidden();
        $this->actingAs($coach)->post(route('admin.renewals.confirm', [$student, $enrollment]))->assertForbidden();
        $this->actingAs($coach)->post(route('admin.renewals.decline', [$student, $enrollment]))->assertForbidden();

        $this->assertTrue($enrollment->fresh()->is_active);
        $this->assertSame('perlu_konfirmasi', $enrollment->fresh()->renewal_status);
    }

    public function test_admin_renewals_index_lists_students_needing_confirmation(): void
    {
        $admin = $this->makeAdmin();
        $student = $this->makeStudent($this->makeParent());
        $class = $this->makeClass($this->makeProgram());
        $enrollment = $this->makeEnrollment($student, $class, 7, 'perlu_konfirmasi');

        $this->actingAs($admin)
            ->get(route('admin.renewals.index'))
            ->assertOk()
            ->assertSee('Perpanjangan Paket')
            ->assertSee($student->full_name)
            ->assertSee($class->name)
            ->assertSee('Konfirmasi Perpanjangan')
            ->assertSee('Tidak Lanjut');

        $enrollment->update(['renewal_status' => 'aktif']);

        $this->actingAs($admin)
            ->get(route('admin.renewals.index'))
            ->assertOk()
            ->assertDontSee($student->full_name);
    }

    public function test_attendance_records_class_student_id_of_active_period_after_renewal(): void
    {
        $admin = $this->makeAdmin();
        $student = $this->makeStudent($this->makeParent());
        $class = $this->makeClass($this->makeProgram());
        $enrollment = $this->makeEnrollment($student, $class, 8, 'perlu_konfirmasi');

        $this->actingAs($admin)
            ->post(route('admin.renewals.confirm', [$student, $enrollment]))
            ->assertRedirect();

        $new = ClassStudent::where('student_id', $student->id)->where('id', '!=', $enrollment->id)->firstOrFail();

        $this->actingAs($admin)
            ->post(route('admin.attendances.store'), [
                'attendance_date' => '2026-08-11',
                'attendance' => [$student->id],
            ])
            ->assertRedirect();

        $attendance = Attendance::where('student_id', $student->id)->whereDate('attendance_date', '2026-08-11')->first();
        $this->assertNotNull($attendance);
        $this->assertSame($new->id, $attendance->class_student_id);
        $this->assertSame(1, $new->fresh()->sessions_completed);
        $this->assertSame(8, $enrollment->fresh()->sessions_completed);
    }
}
