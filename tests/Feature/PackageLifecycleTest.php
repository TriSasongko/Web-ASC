<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\ClassRecommendation;
use App\Models\ClassStudent;
use App\Models\Program;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PackageLifecycleTest extends TestCase
{
    use RefreshDatabase;

    private function makeParent(): User
    {
        return User::factory()->create(['role' => 'orang_tua', 'phone' => '081234567800', 'is_active' => true]);
    }

    private function makeStudent(User $parent, string $name = 'Anak ASC'): Student
    {
        return Student::create([
            'parent_id' => $parent->id,
            'full_name' => $name,
            'birth_date' => '2015-01-01',
            'gender' => 'L',
        ]);
    }

    private function makeEnrollment(Student $student, int $sessionsCompleted = 4): array
    {
        $admin = User::factory()->create(['role' => 'admin', 'is_active' => true]);
        $coach = User::factory()->create(['role' => 'pelatih', 'is_active' => true]);

        $program = Program::firstOrCreate([
            'slug' => 'reguler',
        ], [
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
            'is_active' => true,
        ]);

        $enrollment = ClassStudent::create([
            'class_id' => $class->id,
            'student_id' => $student->id,
            'sessions_completed' => $sessionsCompleted,
            'is_active' => true,
            'renewal_status' => 'belum_konfirmasi',
        ]);

        return compact('admin', 'coach', 'class', 'enrollment');
    }

    public function test_stop_marks_only_that_enrollment_inactive()
    {
        $parent = $this->makeParent();
        $childA = $this->makeStudent($parent, 'Anak A');
        $childB = $this->makeStudent($parent, 'Anak B');

        $a = $this->makeEnrollment($childA);
        $b = $this->makeEnrollment($childB);

        $this->actingAs($a['admin'])
            ->patch(route('admin.class-students.stop', $a['enrollment']))
            ->assertRedirect();

        $this->assertFalse($a['enrollment']->fresh()->is_active);
        $this->assertSame('berhenti', $a['enrollment']->fresh()->renewal_status);

        $this->assertTrue($b['enrollment']->fresh()->is_active);
        $this->assertSame('belum_konfirmasi', $b['enrollment']->fresh()->renewal_status);
        $this->assertTrue($parent->fresh()->is_active);
    }

    public function test_stopped_student_hidden_from_active_program_query()
    {
        $parent = $this->makeParent();
        $childA = $this->makeStudent($parent, 'Anak A');
        $childB = $this->makeStudent($parent, 'Anak B');

        $a = $this->makeEnrollment($childA);
        $this->makeEnrollment($childB);

        $a['enrollment']->update(['is_active' => false, 'renewal_status' => 'berhenti']);

        $this->assertFalse(Student::activeProgram()->where('id', $childA->id)->exists());
        $this->assertTrue(Student::activeProgram()->where('id', $childB->id)->exists());
    }

    public function test_activate_reactivates_stopped_enrollment()
    {
        $parent = $this->makeParent();
        $child = $this->makeStudent($parent);
        $data = $this->makeEnrollment($child);

        $data['enrollment']->update(['is_active' => false, 'renewal_status' => 'berhenti']);

        $this->actingAs($data['admin'])
            ->patch(route('admin.class-students.activate', $data['enrollment']))
            ->assertRedirect();

        $this->assertTrue($data['enrollment']->fresh()->is_active);
        $this->assertSame('belum_konfirmasi', $data['enrollment']->fresh()->renewal_status);
    }

    public function test_renew_creates_new_period_when_package_finished()
    {
        $parent = $this->makeParent();
        $child = $this->makeStudent($parent);
        $data = $this->makeEnrollment($child, 8);

        $this->actingAs($data['admin'])
            ->patch(route('admin.class-students.renew', $data['enrollment']))
            ->assertRedirect();

        $old = $data['enrollment']->fresh();
        $this->assertFalse($old->is_active);
        $this->assertSame('selesai', $old->renewal_status);

        $new = ClassStudent::where('student_id', $child->id)
            ->where('id', '!=', $old->id)
            ->firstOrFail();
        $this->assertTrue($new->is_active);
        $this->assertSame(0, $new->sessions_completed);
        $this->assertSame('aktif', $new->renewal_status);
        $this->assertSame($old->id, $new->renewed_from_id);
    }

    public function test_renew_defers_when_package_has_remaining_sessions()
    {
        $parent = $this->makeParent();
        $child = $this->makeStudent($parent);
        $data = $this->makeEnrollment($child, 7);

        $this->actingAs($data['admin'])
            ->patch(route('admin.class-students.renew', $data['enrollment']))
            ->assertRedirect();

        $fresh = $data['enrollment']->fresh();
        $this->assertTrue($fresh->is_active);
        $this->assertSame('lanjut', $fresh->renewal_status);
        $this->assertSame(7, $fresh->sessions_completed);
        $this->assertSame(1, ClassStudent::where('student_id', $child->id)->count());
    }

    public function test_renew_defer_then_attendance_finishes_old_and_starts_new_period()
    {
        $parent = $this->makeParent();
        $child = $this->makeStudent($parent);
        $data = $this->makeEnrollment($child, 7);

        $this->actingAs($data['admin'])
            ->patch(route('admin.class-students.renew', $data['enrollment']))
            ->assertRedirect();

        // Sesi ke-8: masuk paket lama dan otomatis membuka periode baru.
        $this->actingAs($data['admin'])
            ->post(route('admin.attendances.store'), [
                'attendance_date' => '2026-08-12',
                'attendance' => [$child->id],
            ])
            ->assertRedirect();

        $old = $data['enrollment']->fresh();
        $this->assertFalse($old->is_active);
        $this->assertSame('selesai', $old->renewal_status);
        $this->assertSame(8, $old->sessions_completed);

        $new = ClassStudent::where('student_id', $child->id)
            ->where('id', '!=', $old->id)
            ->firstOrFail();
        $this->assertSame(0, $new->sessions_completed);

        $this->assertSame(1, Attendance::where('student_id', $child->id)
            ->where('class_student_id', $old->id)
            ->whereDate('attendance_date', '2026-08-12')
            ->count());

        // Sesi berikutnya masuk periode paket baru.
        $this->actingAs($data['admin'])
            ->post(route('admin.attendances.store'), [
                'attendance_date' => '2026-08-13',
                'attendance' => [$child->id],
            ])
            ->assertRedirect();

        $this->assertSame(1, $new->fresh()->sessions_completed);
        $this->assertSame(1, Attendance::where('student_id', $child->id)
            ->where('class_student_id', $new->id)
            ->whereDate('attendance_date', '2026-08-13')
            ->count());
    }

    public function test_parent_toggle_hides_all_children_and_restores_them()
    {
        $parent = $this->makeParent();
        $childA = $this->makeStudent($parent, 'Anak A');
        $childB = $this->makeStudent($parent, 'Anak B');

        $this->makeEnrollment($childA);
        $this->makeEnrollment($childB);

        $this->actingAs(User::factory()->create(['role' => 'admin', 'is_active' => true]))
            ->patch(route('admin.parents.toggle-active', $parent))
            ->assertRedirect();

        $this->assertFalse($parent->fresh()->is_active);
        $this->assertFalse(Student::query()->whereIn('id', [$childA->id, $childB->id])->exists());

        $this->actingAs(User::factory()->create(['role' => 'admin', 'is_active' => true]))
            ->patch(route('admin.parents.toggle-active', $parent))
            ->assertRedirect();

        $this->assertTrue($parent->fresh()->is_active);
        $this->assertTrue(Student::query()->whereIn('id', [$childA->id, $childB->id])->exists());
    }

    public function test_coach_can_create_recommendation()
    {
        $parent = $this->makeParent();
        $child = $this->makeStudent($parent);
        $data = $this->makeEnrollment($child, 8);

        $kompetitif = Program::firstOrCreate([
            'slug' => 'kompetitif',
        ], [
            'name' => 'Kompetitif',
            'total_sessions' => null,
            'price' => 300000,
            'billing_type' => 'per_bulan',
            'is_kompetitif' => true,
            'is_active' => true,
        ]);

        $target = SchoolClass::create([
            'program_id' => $kompetitif->id,
            'name' => 'Kompetitif B',
            'level' => 2,
            'is_active' => true,
        ]);

        $this->actingAs($data['coach'])
            ->post(route('pelatih.recommendations.store', [$data['class'], $child]), [
                'recommended_class_id' => $target->id,
                'note' => 'Sudah siap naik level.',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('class_recommendations', [
            'student_id' => $child->id,
            'from_user_id' => $data['coach']->id,
            'current_class_id' => $data['class']->id,
            'recommended_class_id' => $target->id,
            'status' => 'pending',
        ]);
    }

    public function test_admin_can_create_recommendation_by_level()
    {
        $parent = $this->makeParent();
        $child = $this->makeStudent($parent);
        $data = $this->makeEnrollment($child, 8);

        $this->actingAs($data['admin'])
            ->post(route('admin.recommendations.store'), [
                'student_id' => $child->id,
                'current_class_id' => $data['class']->id,
                'recommended_level' => 2,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('class_recommendations', [
            'student_id' => $child->id,
            'from_user_id' => $data['admin']->id,
            'recommended_level' => 2,
            'status' => 'pending',
        ]);
    }

    public function test_admin_can_approve_recommendation_and_move_student()
    {
        $parent = $this->makeParent();
        $child = $this->makeStudent($parent);
        $data = $this->makeEnrollment($child, 8);

        $kompetitif = Program::firstOrCreate([
            'slug' => 'kompetitif',
        ], [
            'name' => 'Kompetitif',
            'total_sessions' => null,
            'price' => 300000,
            'billing_type' => 'per_bulan',
            'is_kompetitif' => true,
            'is_active' => true,
        ]);

        $target = SchoolClass::create([
            'program_id' => $kompetitif->id,
            'name' => 'Kompetitif B',
            'level' => 2,
            'is_active' => true,
        ]);

        $rec = ClassRecommendation::create([
            'student_id' => $child->id,
            'from_user_id' => $data['coach']->id,
            'current_class_id' => $data['class']->id,
            'recommended_class_id' => $target->id,
            'recommended_level' => 2,
            'status' => 'pending',
        ]);

        $this->actingAs($data['admin'])
            ->post(route('admin.recommendations.approve', $rec))
            ->assertRedirect();

        $this->assertSame('menunggu_ortu', $rec->fresh()->status);
        $this->assertSame($data['admin']->id, $rec->fresh()->approved_by);
        $this->assertNull($rec->fresh()->moved_at);
        $this->assertTrue($data['enrollment']->fresh()->is_active);

        $this->actingAs($data['admin'])
            ->post(route('admin.recommendations.confirm', $rec))
            ->assertRedirect();

        $this->assertSame('diterima', $rec->fresh()->status);
        $this->assertNotNull($rec->fresh()->moved_at);

        $this->assertFalse($data['enrollment']->fresh()->is_active);
        $this->assertSame('pindah', $data['enrollment']->fresh()->renewal_status);

        $this->assertDatabaseHas('class_student', [
            'class_id' => $target->id,
            'student_id' => $child->id,
            'level' => 2,
            'is_active' => true,
        ]);

        $this->assertDatabaseHas('registrations', [
            'student_id' => $child->id,
            'program_id' => $kompetitif->id,
            'status' => 'diterima',
        ]);
    }

    public function test_admin_can_approve_level_based_recommendation_in_same_program()
    {
        $parent = $this->makeParent();
        $child = $this->makeStudent($parent);
        $data = $this->makeEnrollment($child, 8);

        $nextClass = SchoolClass::create([
            'program_id' => $data['class']->program_id,
            'name' => 'Reguler B',
            'level' => 2,
            'is_active' => true,
        ]);

        $rec = ClassRecommendation::create([
            'student_id' => $child->id,
            'from_user_id' => $data['coach']->id,
            'current_class_id' => $data['class']->id,
            'recommended_level' => 2,
            'status' => 'pending',
        ]);

        $this->actingAs($data['admin'])
            ->post(route('admin.recommendations.approve', $rec))
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertSame('menunggu_ortu', $rec->fresh()->status);
        $this->assertSame($data['admin']->id, $rec->fresh()->approved_by);

        $this->actingAs($data['admin'])
            ->post(route('admin.recommendations.confirm', $rec))
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertSame('diterima', $rec->fresh()->status);
        $this->assertFalse($data['enrollment']->fresh()->is_active);
        $this->assertDatabaseHas('class_student', [
            'class_id' => $nextClass->id,
            'student_id' => $child->id,
            'level' => 2,
            'is_active' => true,
        ]);
    }

    public function test_admin_can_reject_recommendation()
    {
        $parent = $this->makeParent();
        $child = $this->makeStudent($parent);
        $data = $this->makeEnrollment($child);

        $rec = ClassRecommendation::create([
            'student_id' => $child->id,
            'from_user_id' => $data['coach']->id,
            'current_class_id' => $data['class']->id,
            'recommended_level' => 2,
            'status' => 'pending',
        ]);

        $this->actingAs($data['admin'])
            ->post(route('admin.recommendations.reject', $rec))
            ->assertRedirect();

        $this->assertSame('ditolak', $rec->fresh()->status);
        $this->assertSame($data['admin']->id, $rec->fresh()->approved_by);
        $this->assertTrue($data['enrollment']->fresh()->is_active);
    }

    public function test_coach_cannot_recommend_same_or_lower_level()
    {
        $parent = $this->makeParent();
        $child = $this->makeStudent($parent);
        $data = $this->makeEnrollment($child);

        $this->actingAs($data['coach'])
            ->post(route('pelatih.recommendations.store', [$data['class'], $child]), [
                'recommended_level' => 1,
            ])
            ->assertSessionHasErrors('recommended_level');

        $this->assertDatabaseMissing('class_recommendations', ['student_id' => $child->id]);
    }

    public function test_duplicate_pending_recommendation_is_rejected()
    {
        $parent = $this->makeParent();
        $child = $this->makeStudent($parent);
        $data = $this->makeEnrollment($child, 8);

        ClassRecommendation::create([
            'student_id' => $child->id,
            'from_user_id' => $data['coach']->id,
            'current_class_id' => $data['class']->id,
            'recommended_level' => 2,
            'status' => 'pending',
        ]);

        $kompetitif = Program::firstOrCreate([
            'slug' => 'kompetitif',
        ], [
            'name' => 'Kompetitif',
            'total_sessions' => null,
            'price' => 300000,
            'billing_type' => 'per_bulan',
            'is_kompetitif' => true,
            'is_active' => true,
        ]);

        $target = SchoolClass::create([
            'program_id' => $kompetitif->id,
            'name' => 'Kompetitif B',
            'level' => 2,
            'is_active' => true,
        ]);

        $this->actingAs($data['coach'])
            ->post(route('pelatih.recommendations.store', [$data['class'], $child]), [
                'recommended_class_id' => $target->id,
            ])
            ->assertStatus(422);

        $this->assertSame(1, ClassRecommendation::where('student_id', $child->id)->count());
    }

    public function test_attendance_is_blocked_when_package_total_is_finished()
    {
        $parent = $this->makeParent();
        $child = $this->makeStudent($parent);
        $data = $this->makeEnrollment($child, 8);

        $this->actingAs($data['admin'])
            ->post(route('admin.attendances.store'), [
                'attendance_date' => '2026-08-09',
                'attendance' => [$child->id],
            ])
            ->assertSessionHasErrors('attendance');

        $this->assertSame(8, $data['enrollment']->fresh()->sessions_completed);
        $this->assertTrue($data['enrollment']->fresh()->is_active);
        $this->assertSame(0, Attendance::where('student_id', $child->id)
            ->whereDate('attendance_date', '2026-08-09')
            ->count());
    }

    public function test_attendance_allows_the_last_session_before_package_finishes()
    {
        $parent = $this->makeParent();
        $child = $this->makeStudent($parent);
        $data = $this->makeEnrollment($child, 7);

        $this->actingAs($data['admin'])
            ->post(route('admin.attendances.store'), [
                'attendance_date' => '2026-08-09',
                'attendance' => [$child->id],
            ])
            ->assertRedirect();

        $this->assertSame(8, $data['enrollment']->fresh()->sessions_completed);
    }

    public function test_attendance_is_blocked_for_finished_package_recorded_by_coach()
    {
        $parent = $this->makeParent();
        $child = $this->makeStudent($parent);
        $data = $this->makeEnrollment($child, 8);

        $this->actingAs($data['coach'])
            ->post(route('pelatih.attendances.store'), [
                'attendance_date' => '2026-08-09',
                'attendance' => [$child->id],
            ])
            ->assertSessionHasErrors('attendance');

        $this->assertSame(0, Attendance::where('student_id', $child->id)
            ->whereDate('attendance_date', '2026-08-09')
            ->count());
    }

    public function test_attendance_allowed_when_finished_package_has_status_lanjut()
    {
        $parent = $this->makeParent();
        $child = $this->makeStudent($parent);
        $data = $this->makeEnrollment($child, 8);

        $data['enrollment']->update(['renewal_status' => 'lanjut']);

        $this->actingAs($data['admin'])
            ->post(route('admin.attendances.store'), [
                'attendance_date' => '2026-08-09',
                'attendance' => [$child->id],
            ])
            ->assertRedirect();

        $next = ClassStudent::where('student_id', $child->id)
            ->where('id', '!=', $data['enrollment']->id)
            ->firstOrFail();

        $this->assertFalse($data['enrollment']->fresh()->is_active);
        $this->assertTrue($next->is_active);
        $this->assertSame(1, Attendance::where('student_id', $child->id)
            ->where('class_student_id', $next->id)
            ->whereDate('attendance_date', '2026-08-09')
            ->count());
    }

    public function test_create_page_marks_finished_package_student_as_paket_habis()
    {
        $parent = $this->makeParent();
        $child = $this->makeStudent($parent);
        $data = $this->makeEnrollment($child, 8);

        $this->actingAs($data['admin'])
            ->get(route('admin.attendances.create'))
            ->assertOk()
            ->assertSee('Paket habis');
    }

    public function test_attendance_increments_active_package_sessions()
    {
        $parent = $this->makeParent();
        $child = $this->makeStudent($parent);
        $data = $this->makeEnrollment($child, 3);

        $this->actingAs($data['admin'])
            ->post(route('admin.attendances.store'), [
                'attendance_date' => '2026-08-09',
                'attendance' => [$child->id],
            ])
            ->assertRedirect();

        $this->assertSame(4, $data['enrollment']->fresh()->sessions_completed);
    }

    public function test_substitute_coach_can_record_attendance_for_another_coach_class()
    {
        $parent = $this->makeParent();
        $child = $this->makeStudent($parent);
        $data = $this->makeEnrollment($child, 3);

        $substitute = User::factory()->create(['role' => 'pelatih', 'is_active' => true]);

        $this->actingAs($substitute)
            ->post(route('pelatih.attendances.store'), [
                'attendance_date' => '2026-08-09',
                'attendance' => [$child->id],
            ])
            ->assertRedirect(route('pelatih.attendances.history'));

        $this->assertTrue(Attendance::where('student_id', $child->id)
            ->where('recorded_by', $substitute->id)
            ->whereDate('attendance_date', '2026-08-09')
            ->where('class_id', $data['class']->id)
            ->exists());

        $this->assertSame(4, $data['enrollment']->fresh()->sessions_completed);
    }

    public function test_coach_cannot_recommend_when_package_not_finished()
    {
        $parent = $this->makeParent();
        $child = $this->makeStudent($parent);
        $data = $this->makeEnrollment($child, 4);

        $kompetitif = Program::firstOrCreate([
            'slug' => 'kompetitif',
        ], [
            'name' => 'Kompetitif',
            'total_sessions' => null,
            'price' => 300000,
            'billing_type' => 'per_bulan',
            'is_kompetitif' => true,
            'is_active' => true,
        ]);

        $target = SchoolClass::create([
            'program_id' => $kompetitif->id,
            'name' => 'Kompetitif B',
            'level' => 2,
            'is_active' => true,
        ]);

        $this->actingAs($data['coach'])
            ->post(route('pelatih.recommendations.store', [$data['class'], $child]), [
                'recommended_class_id' => $target->id,
            ])
            ->assertStatus(422);

        $this->assertDatabaseMissing('class_recommendations', ['student_id' => $child->id]);
    }

    public function test_admin_cannot_create_recommendation_when_package_not_finished()
    {
        $parent = $this->makeParent();
        $child = $this->makeStudent($parent);
        $data = $this->makeEnrollment($child, 4);

        $this->actingAs($data['admin'])
            ->post(route('admin.recommendations.store'), [
                'student_id' => $child->id,
                'current_class_id' => $data['class']->id,
                'recommended_level' => 2,
            ])
            ->assertSessionHasErrors('recommended_class_id');

        $this->assertDatabaseMissing('class_recommendations', ['student_id' => $child->id]);
    }

    public function test_second_coach_cannot_recommend_when_recommendation_active()
    {
        $parent = $this->makeParent();
        $child = $this->makeStudent($parent);
        $data = $this->makeEnrollment($child, 8);

        ClassRecommendation::create([
            'student_id' => $child->id,
            'from_user_id' => $data['coach']->id,
            'current_class_id' => $data['class']->id,
            'recommended_level' => 2,
            'status' => 'menunggu_ortu',
        ]);

        $kompetitif = Program::firstOrCreate([
            'slug' => 'kompetitif',
        ], [
            'name' => 'Kompetitif',
            'total_sessions' => null,
            'price' => 300000,
            'billing_type' => 'per_bulan',
            'is_kompetitif' => true,
            'is_active' => true,
        ]);

        $target = SchoolClass::create([
            'program_id' => $kompetitif->id,
            'name' => 'Kompetitif B',
            'level' => 2,
            'is_active' => true,
        ]);

        $otherCoach = User::factory()->create(['role' => 'pelatih', 'is_active' => true]);

        $this->actingAs($otherCoach)
            ->post(route('pelatih.recommendations.store', [$data['class'], $child]), [
                'recommended_class_id' => $target->id,
            ])
            ->assertStatus(422);

        $this->assertSame(1, ClassRecommendation::where('student_id', $child->id)->count());
    }
}
