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

class AdminPromotionTest extends TestCase
{
    use RefreshDatabase;

    private function makeAdmin(): User
    {
        return User::factory()->create(['role' => 'admin', 'is_active' => true]);
    }

    private function makeEnrollment(int $level = 1, int $sessionsCompleted = 8): array
    {
        $parent = User::factory()->create(['role' => 'orang_tua', 'is_active' => true]);

        $student = Student::create([
            'parent_id' => $parent->id,
            'full_name' => 'Anak ASC',
            'birth_date' => '2015-01-01',
            'gender' => 'L',
        ]);

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
            'level' => $level,
            'is_active' => true,
        ]);

        $enrollment = ClassStudent::create([
            'class_id' => $class->id,
            'student_id' => $student->id,
            'level' => $level,
            'sessions_completed' => $sessionsCompleted,
            'is_active' => true,
            'renewal_status' => 'belum_konfirmasi',
        ]);

        return compact('student', 'class', 'enrollment', 'program');
    }

    public function test_admin_direct_move_creates_recommendation_waiting_for_parent()
    {
        $admin = $this->makeAdmin();
        $data = $this->makeEnrollment();

        $target = SchoolClass::create([
            'program_id' => $data['program']->id,
            'name' => 'Reguler B',
            'level' => 2,
            'is_active' => true,
        ]);

        $this->actingAs($admin)
            ->post(route('admin.class-students.move', $data['enrollment']), [
                'target_class_id' => $target->id,
            ])
            ->assertRedirect(route('admin.recommendations.index'))
            ->assertSessionHas('success');

        $rec = ClassRecommendation::where('student_id', $data['student']->id)->first();
        $this->assertNotNull($rec);
        $this->assertSame('menunggu_ortu', $rec->status);
        $this->assertSame($data['class']->id, $rec->current_class_id);
        $this->assertSame($target->id, $rec->recommended_class_id);
        $this->assertSame(2, $rec->recommended_level);
        $this->assertSame($admin->id, $rec->approved_by);

        $this->assertTrue($data['enrollment']->fresh()->is_active);
        $this->assertDatabaseMissing('class_student', [
            'class_id' => $target->id,
            'student_id' => $data['student']->id,
        ]);

        $this->actingAs($admin)
            ->post(route('admin.recommendations.confirm', $rec))
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertFalse($data['enrollment']->fresh()->is_active);
        $this->assertSame('pindah', $data['enrollment']->fresh()->renewal_status);

        $this->assertDatabaseHas('class_student', [
            'class_id' => $target->id,
            'student_id' => $data['student']->id,
            'level' => 2,
            'is_active' => true,
        ]);

        $this->assertDatabaseHas('registrations', [
            'student_id' => $data['student']->id,
            'program_id' => $target->program_id,
            'status' => 'diterima',
        ]);
    }

    public function test_move_rejected_when_target_level_is_not_higher()
    {
        $admin = $this->makeAdmin();
        $data = $this->makeEnrollment();

        $target = SchoolClass::create([
            'program_id' => $data['program']->id,
            'name' => 'Reguler Sejajar',
            'level' => 1,
            'is_active' => true,
        ]);

        $this->actingAs($admin)
            ->post(route('admin.class-students.move', $data['enrollment']), [
                'target_class_id' => $target->id,
            ])
            ->assertRedirect()
            ->assertSessionHas('error');

        $this->assertTrue($data['enrollment']->fresh()->is_active);
        $this->assertDatabaseMissing('class_recommendations', ['student_id' => $data['student']->id]);
    }

    public function test_move_rejected_when_package_not_finished()
    {
        $admin = $this->makeAdmin();
        $data = $this->makeEnrollment(sessionsCompleted: 4);

        $target = SchoolClass::create([
            'program_id' => $data['program']->id,
            'name' => 'Reguler B',
            'level' => 2,
            'is_active' => true,
        ]);

        $this->actingAs($admin)
            ->post(route('admin.class-students.move', $data['enrollment']), [
                'target_class_id' => $target->id,
            ])
            ->assertRedirect()
            ->assertSessionHas('error', 'Paket Reguler belum habis, siswa belum dapat dinaikkan kelas.');

        $this->assertTrue($data['enrollment']->fresh()->is_active);
        $this->assertDatabaseMissing('class_recommendations', ['student_id' => $data['student']->id]);
    }

    public function test_move_rejected_when_recommendation_already_active()
    {
        $admin = $this->makeAdmin();
        $data = $this->makeEnrollment();

        ClassRecommendation::create([
            'student_id' => $data['student']->id,
            'from_user_id' => $admin->id,
            'current_class_id' => $data['class']->id,
            'recommended_level' => 2,
            'status' => 'menunggu_ortu',
        ]);

        $target = SchoolClass::create([
            'program_id' => $data['program']->id,
            'name' => 'Reguler B',
            'level' => 2,
            'is_active' => true,
        ]);

        $this->actingAs($admin)
            ->post(route('admin.class-students.move', $data['enrollment']), [
                'target_class_id' => $target->id,
            ])
            ->assertRedirect()
            ->assertSessionHas('error', 'Masih ada rekomendasi naik kelas aktif untuk siswa ini.');

        $this->assertSame(1, ClassRecommendation::where('student_id', $data['student']->id)->count());
    }

    public function test_move_rejected_for_elite_student()
    {
        $admin = $this->makeAdmin();
        $data = $this->makeEnrollment(level: 3);

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
            'name' => 'Kompetitif Elite',
            'level' => 3,
            'is_active' => true,
        ]);

        $this->actingAs($admin)
            ->post(route('admin.class-students.move', $data['enrollment']), [
                'target_class_id' => $target->id,
            ])
            ->assertRedirect()
            ->assertSessionHas('error', 'Siswa level Elite tidak dapat dinaikkan kelas.');

        $this->assertTrue($data['enrollment']->fresh()->is_active);
    }

    public function test_non_admin_cannot_move_student()
    {
        $data = $this->makeEnrollment();
        $coach = User::factory()->create(['role' => 'pelatih', 'is_active' => true]);

        $this->actingAs($coach)
            ->post(route('admin.class-students.move', $data['enrollment']), [
                'target_class_id' => $data['class']->id,
            ])
            ->assertForbidden();
    }
}
