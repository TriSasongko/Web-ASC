<?php

namespace Tests\Feature;

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
            'coach_id' => $coach->id,
            'name' => 'Reguler A',
            'level' => 1,
            'capacity' => 10,
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

    public function test_renew_resets_sessions_and_reactivates()
    {
        $parent = $this->makeParent();
        $child = $this->makeStudent($parent);
        $data = $this->makeEnrollment($child, 8);

        $this->actingAs($data['admin'])
            ->patch(route('admin.class-students.renew', $data['enrollment']))
            ->assertRedirect();

        $fresh = $data['enrollment']->fresh();
        $this->assertSame(0, $fresh->sessions_completed);
        $this->assertTrue($fresh->is_active);
        $this->assertSame('lanjut', $fresh->renewal_status);
        $this->assertNotNull($fresh->renewed_at);
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
        $data = $this->makeEnrollment($child);

        $target = SchoolClass::create([
            'program_id' => $data['class']->program_id,
            'coach_id' => $data['coach']->id,
            'name' => 'Reguler B',
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
        $data = $this->makeEnrollment($child);

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
        $data = $this->makeEnrollment($child);

        $target = SchoolClass::create([
            'program_id' => $data['class']->program_id,
            'coach_id' => $data['coach']->id,
            'name' => 'Reguler B',
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

        $this->assertSame('diterima', $rec->fresh()->status);
        $this->assertSame($data['admin']->id, $rec->fresh()->approved_by);
        $this->assertNotNull($rec->fresh()->moved_at);

        $this->assertFalse($data['enrollment']->fresh()->is_active);
        $this->assertSame('pindah', $data['enrollment']->fresh()->renewal_status);

        $this->assertDatabaseHas('class_student', [
            'class_id' => $target->id,
            'student_id' => $child->id,
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
        $data = $this->makeEnrollment($child);

        ClassRecommendation::create([
            'student_id' => $child->id,
            'from_user_id' => $data['coach']->id,
            'current_class_id' => $data['class']->id,
            'recommended_level' => 2,
            'status' => 'pending',
        ]);

        $target = SchoolClass::create([
            'program_id' => $data['class']->program_id,
            'coach_id' => $data['coach']->id,
            'name' => 'Reguler C',
            'level' => 3,
            'is_active' => true,
        ]);

        $this->actingAs($data['coach'])
            ->post(route('pelatih.recommendations.store', [$data['class'], $child]), [
                'recommended_class_id' => $target->id,
            ])
            ->assertStatus(422);

        $this->assertSame(1, ClassRecommendation::where('student_id', $child->id)->count());
    }

    public function test_attendance_does_not_exceed_package_total()
    {
        $parent = $this->makeParent();
        $child = $this->makeStudent($parent);
        $data = $this->makeEnrollment($child, 8);

        $this->actingAs($data['admin'])
            ->post(route('admin.attendances.store', $data['class']), [
                'attendance_date' => '2026-08-09',
                'attendance' => [$child->id],
            ])
            ->assertRedirect();

        $this->assertSame(8, $data['enrollment']->fresh()->sessions_completed);
        $this->assertTrue($data['enrollment']->fresh()->is_active);
    }
}
