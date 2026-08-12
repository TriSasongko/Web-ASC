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

    public function test_admin_attendance_flags_enrollment_for_renewal_in_realtime(): void
    {
        $admin = $this->makeAdmin();
        $student = $this->makeStudent($this->makeParent());
        $class = $this->makeClass($this->makeProgram());
        $enrollment = $this->makeEnrollment($student, $class, 7, 'belum_konfirmasi');

        $this->actingAs($admin)
            ->post(route('admin.attendances.store'), [
                'attendance_date' => '2026-08-11',
                'attendance' => [$student->id],
            ])
            ->assertRedirect();

        $fresh = $enrollment->fresh();
        $this->assertSame(8, $fresh->sessions_completed);
        $this->assertSame('perlu_konfirmasi', $fresh->renewal_status);
    }

    public function test_pelatih_attendance_flags_enrollment_for_renewal_in_realtime(): void
    {
        $coach = User::factory()->create(['role' => 'pelatih', 'is_active' => true]);
        $student = $this->makeStudent($this->makeParent());
        $class = $this->makeClass($this->makeProgram());
        $enrollment = $this->makeEnrollment($student, $class, 7, 'belum_konfirmasi');

        $this->actingAs($coach)
            ->post(route('pelatih.attendances.store'), [
                'attendance_date' => '2026-08-11',
                'attendance' => [$student->id],
            ])
            ->assertRedirect();

        $this->assertSame('perlu_konfirmasi', $enrollment->fresh()->renewal_status);
    }

    public function test_attendance_below_threshold_does_not_flag_for_renewal(): void
    {
        $admin = $this->makeAdmin();
        $student = $this->makeStudent($this->makeParent());
        $class = $this->makeClass($this->makeProgram());
        $enrollment = $this->makeEnrollment($student, $class, 5, 'belum_konfirmasi');

        $this->actingAs($admin)
            ->post(route('admin.attendances.store'), [
                'attendance_date' => '2026-08-11',
                'attendance' => [$student->id],
            ])
            ->assertRedirect();

        $fresh = $enrollment->fresh();
        $this->assertSame(6, $fresh->sessions_completed);
        $this->assertSame('belum_konfirmasi', $fresh->renewal_status);
    }

    public function test_confirm_renewal_with_remaining_sessions_defers_until_finished(): void
    {
        $admin = $this->makeAdmin();
        $student = $this->makeStudent($this->makeParent());
        $class = $this->makeClass($this->makeProgram());
        $enrollment = $this->makeEnrollment($student, $class, 7, 'perlu_konfirmasi');

        $this->actingAs($admin)
            ->post(route('admin.renewals.confirm', [$student, $enrollment]))
            ->assertRedirect()
            ->assertSessionHas('success');

        $fresh = $enrollment->fresh();
        $this->assertTrue($fresh->is_active);
        $this->assertSame('lanjut', $fresh->renewal_status);
        $this->assertNull($fresh->ended_at);
        $this->assertSame(1, ClassStudent::where('student_id', $student->id)->count());
    }

    public function test_attendance_on_lanjut_enrollment_switches_period_when_finished(): void
    {
        $admin = $this->makeAdmin();
        $student = $this->makeStudent($this->makeParent());
        $class = $this->makeClass($this->makeProgram());
        $enrollment = $this->makeEnrollment($student, $class, 7, 'lanjut');

        $this->actingAs($admin)
            ->post(route('admin.attendances.store'), [
                'attendance_date' => '2026-08-11',
                'attendance' => [$student->id],
            ])
            ->assertRedirect();

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
        $this->assertSame($old->id, $new->renewed_from_id);

        $attendance = Attendance::where('student_id', $student->id)->whereDate('attendance_date', '2026-08-11')->first();
        $this->assertSame($old->id, $attendance->class_student_id);
    }

    public function test_attendance_on_finished_lanjut_enrollment_starts_new_package_period(): void
    {
        $admin = $this->makeAdmin();
        $student = $this->makeStudent($this->makeParent());
        $class = $this->makeClass($this->makeProgram());
        $enrollment = $this->makeEnrollment($student, $class, 8, 'lanjut');

        $this->actingAs($admin)
            ->post(route('admin.attendances.store'), [
                'attendance_date' => '2026-08-12',
                'attendance' => [$student->id],
            ])
            ->assertRedirect();

        $old = $enrollment->fresh();
        $this->assertFalse($old->is_active);
        $this->assertSame('selesai', $old->renewal_status);

        $new = ClassStudent::where('student_id', $student->id)->where('id', '!=', $old->id)->first();
        $this->assertNotNull($new);
        $this->assertTrue($new->is_active);
        $this->assertSame(1, $new->sessions_completed);

        $attendance = Attendance::where('student_id', $student->id)->whereDate('attendance_date', '2026-08-12')->first();
        $this->assertSame($new->id, $attendance->class_student_id);
    }

    public function test_attendance_on_lanjut_enrollment_below_total_does_not_switch(): void
    {
        $admin = $this->makeAdmin();
        $student = $this->makeStudent($this->makeParent());
        $class = $this->makeClass($this->makeProgram());
        $enrollment = $this->makeEnrollment($student, $class, 6, 'lanjut');

        $this->actingAs($admin)
            ->post(route('admin.attendances.store'), [
                'attendance_date' => '2026-08-11',
                'attendance' => [$student->id],
            ])
            ->assertRedirect();

        $fresh = $enrollment->fresh();
        $this->assertSame(7, $fresh->sessions_completed);
        $this->assertTrue($fresh->is_active);
        $this->assertSame('lanjut', $fresh->renewal_status);
        $this->assertSame(1, ClassStudent::where('student_id', $student->id)->count());
    }

    public function test_renewal_check_does_not_reflag_lanjut_enrollments(): void
    {
        $class = $this->makeClass($this->makeProgram());
        $enrollment = $this->makeEnrollment($this->makeStudent($this->makeParent()), $class, 8, 'lanjut');

        $this->artisan('renewal:check')->assertExitCode(0);

        $this->assertSame('lanjut', $enrollment->fresh()->renewal_status);
    }

    public function test_student_rekap_shows_attendance_per_period_after_renewal(): void
    {
        $admin = $this->makeAdmin();
        $student = $this->makeStudent($this->makeParent());
        $class = $this->makeClass($this->makeProgram());
        $enrollment = $this->makeEnrollment($student, $class, 8, 'perlu_konfirmasi');

        $this->actingAs($admin)
            ->post(route('admin.renewals.confirm', [$student, $enrollment]))
            ->assertRedirect();

        $new = ClassStudent::where('student_id', $student->id)->where('id', '!=', $enrollment->id)->firstOrFail();

        Attendance::create([
            'class_id' => $class->id,
            'class_student_id' => $enrollment->id,
            'student_id' => $student->id,
            'recorded_by' => $admin->id,
            'attendance_date' => '2026-08-01',
            'session_number' => 1,
        ]);

        Attendance::create([
            'class_id' => $class->id,
            'class_student_id' => $new->id,
            'student_id' => $student->id,
            'recorded_by' => $admin->id,
            'attendance_date' => '2026-08-02',
            'session_number' => 1,
        ]);

        $content = $this->actingAs($admin)
            ->get(route('admin.students.show', $student))
            ->assertOk()
            ->getContent();

        $this->assertSame(1, substr_count($content, '01/08/2026'));
        $this->assertSame(1, substr_count($content, '02/08/2026'));
        $this->assertStringContainsString('Riwayat paket', $content);
        $this->assertStringContainsString('Selesai', $content);
        $this->assertStringNotContainsString('Paket habis — Konfirmasi', $content);
    }

    public function test_student_rekap_attaches_legacy_attendance_by_period_date_window(): void
    {
        $admin = $this->makeAdmin();
        $student = $this->makeStudent($this->makeParent());
        $class = $this->makeClass($this->makeProgram());
        $enrollment = $this->makeEnrollment($student, $class, 8, 'perlu_konfirmasi');

        $this->actingAs($admin)
            ->post(route('admin.renewals.confirm', [$student, $enrollment]))
            ->assertRedirect();

        Attendance::create([
            'class_id' => $class->id,
            'class_student_id' => null,
            'student_id' => $student->id,
            'recorded_by' => $admin->id,
            'attendance_date' => '2026-07-01',
            'session_number' => 1,
        ]);

        $content = $this->actingAs($admin)
            ->get(route('admin.students.show', $student))
            ->assertOk()
            ->getContent();

        $this->assertSame(1, substr_count($content, '01/07/2026'));
    }

    public function test_student_rekap_uses_chronological_order_not_session_number(): void
    {
        $admin = $this->makeAdmin();
        $student = $this->makeStudent($this->makeParent());
        $class = $this->makeClass($this->makeProgram());
        $enrollment = $this->makeEnrollment($student, $class, 2, 'aktif');

        Attendance::create([
            'class_id' => $class->id,
            'class_student_id' => $enrollment->id,
            'student_id' => $student->id,
            'recorded_by' => $admin->id,
            'attendance_date' => '2026-08-01',
            'session_number' => 1,
        ]);

        Attendance::create([
            'class_id' => $class->id,
            'class_student_id' => $enrollment->id,
            'student_id' => $student->id,
            'recorded_by' => $admin->id,
            'attendance_date' => '2026-08-08',
            'session_number' => 1,
        ]);

        $content = $this->actingAs($admin)
            ->get(route('admin.students.show', $student))
            ->assertOk()
            ->getContent();

        $this->assertSame(1, substr_count($content, '01/08/2026'));
        $this->assertSame(1, substr_count($content, '08/08/2026'));
    }
}
