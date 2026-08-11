<?php

namespace Tests\Feature;

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

    private function makeEnrollment(int $level = 1): array
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
            'capacity' => 10,
            'is_active' => true,
        ]);

        $enrollment = ClassStudent::create([
            'class_id' => $class->id,
            'student_id' => $student->id,
            'level' => $level,
            'sessions_completed' => 4,
            'is_active' => true,
            'renewal_status' => 'belum_konfirmasi',
        ]);

        return compact('student', 'class', 'enrollment', 'program');
    }

    public function test_admin_can_move_student_directly_to_higher_class()
    {
        $admin = $this->makeAdmin();
        $data = $this->makeEnrollment();

        $target = SchoolClass::create([
            'program_id' => $data['program']->id,
            'name' => 'Reguler B',
            'level' => 2,
            'capacity' => 10,
            'is_active' => true,
        ]);

        $this->actingAs($admin)
            ->post(route('admin.class-students.move', $data['enrollment']), [
                'target_class_id' => $target->id,
            ])
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
            'capacity' => 10,
            'is_active' => true,
        ]);

        $this->actingAs($admin)
            ->post(route('admin.class-students.move', $data['enrollment']), [
                'target_class_id' => $target->id,
            ])
            ->assertRedirect()
            ->assertSessionHas('error');

        $this->assertTrue($data['enrollment']->fresh()->is_active);
    }

    public function test_move_rejected_when_target_class_is_full()
    {
        $admin = $this->makeAdmin();
        $data = $this->makeEnrollment();

        $otherParent = User::factory()->create(['role' => 'orang_tua', 'is_active' => true]);
        $otherStudent = Student::create([
            'parent_id' => $otherParent->id,
            'full_name' => 'Anak Lain',
            'birth_date' => '2015-01-01',
            'gender' => 'P',
        ]);

        $target = SchoolClass::create([
            'program_id' => $data['program']->id,
            'name' => 'Reguler B',
            'level' => 2,
            'capacity' => 1,
            'is_active' => true,
        ]);

        $target->students()->attach($otherStudent->id, [
            'level' => 2,
            'sessions_completed' => 0,
            'is_active' => true,
        ]);

        $this->actingAs($admin)
            ->post(route('admin.class-students.move', $data['enrollment']), [
                'target_class_id' => $target->id,
            ])
            ->assertRedirect()
            ->assertSessionHas('error', 'Kapasitas kelas target sudah penuh.');

        $this->assertTrue($data['enrollment']->fresh()->is_active);
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
            'capacity' => 10,
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
