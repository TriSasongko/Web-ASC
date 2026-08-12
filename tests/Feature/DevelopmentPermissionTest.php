<?php

namespace Tests\Feature;

use App\Models\Development;
use App\Models\Program;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DevelopmentPermissionTest extends TestCase
{
    use RefreshDatabase;

    private function makeCoach(bool $canAssess = false): User
    {
        return User::factory()->create([
            'role' => 'pelatih',
            'is_active' => true,
            'can_assess_developments' => $canAssess,
        ]);
    }

    private function makeAdmin(): User
    {
        return User::factory()->create(['role' => 'admin', 'is_active' => true]);
    }

    private function makeClassAndStudent(): array
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
            'level' => 1,
            'is_active' => true,
        ]);

        return [$class, $student];
    }

    public function test_coach_without_permission_cannot_access_development_index()
    {
        $coach = $this->makeCoach(false);

        $this->actingAs($coach)
            ->get(route('pelatih.developments.index'))
            ->assertForbidden();
    }

    public function test_coach_without_permission_cannot_access_create_or_store()
    {
        [$class, $student] = $this->makeClassAndStudent();
        $coach = $this->makeCoach(false);

        $this->actingAs($coach)
            ->get(route('pelatih.developments.create', [$class, $student]))
            ->assertForbidden();

        $this->actingAs($coach)
            ->post(route('pelatih.developments.store', [$class, $student]), [
                'period' => 'Agustus 2026',
                'coach_note' => 'Test',
            ])
            ->assertForbidden();
    }

    public function test_coach_with_permission_can_access_development_pages()
    {
        [$class, $student] = $this->makeClassAndStudent();
        $coach = $this->makeCoach(true);

        $this->actingAs($coach)
            ->get(route('pelatih.developments.index'))
            ->assertOk();

        $this->actingAs($coach)
            ->get(route('pelatih.developments.create', [$class, $student]))
            ->assertOk();
    }

    public function test_admin_development_index_has_view_perkembangan_button()
    {
        $admin = $this->makeAdmin();
        $coach = $this->makeCoach(true);
        [$class, $student] = $this->makeClassAndStudent();

        foreach (['Juli 2026', 'Agustus 2026'] as $period) {
            Development::create([
                'class_id' => $class->id,
                'student_id' => $student->id,
                'coach_id' => $coach->id,
                'period' => $period,
            ]);
        }

        $this->actingAs($admin)
            ->get(route('admin.developments.index'))
            ->assertOk()
            ->assertSee('Lihat Perkembangan')
            // Dua periode untuk anak yang sama tampil dalam satu baris (bukan dua baris),
            // dan periode terbaru (Agustus) yang jadi default link E-Raport di index.
            ->assertViewHas('students', function ($students) {
                if ($students->total() !== 1) {
                    return false;
                }

                return $students->first()->developments->first()->period === 'Agustus 2026';
            });

        // Periode-periode terlihat dari halaman riwayat (lewat "Lihat Perkembangan"), default tab = terbaru.
        $this->actingAs($admin)
            ->get(route('admin.classes.developments.history', [$class, $student]))
            ->assertOk()
            ->assertSee('Juli 2026')
            ->assertSee('Agustus 2026')
            ->assertViewHas('developments', fn ($developments) => $developments->first()->period === 'Agustus 2026');
    }

    public function test_admin_can_toggle_coach_development_permission()
    {
        $admin = $this->makeAdmin();
        $coach = $this->makeCoach(false);

        $this->actingAs($admin)
            ->patch(route('admin.coaches.toggle-development', $coach))
            ->assertRedirect();

        $this->assertTrue($coach->fresh()->can_assess_developments);

        $this->actingAs($admin)
            ->patch(route('admin.coaches.toggle-development', $coach))
            ->assertRedirect();

        $this->assertFalse($coach->fresh()->can_assess_developments);
    }
}
