<?php

namespace Tests\Feature;

use App\Models\Program;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
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

    public function test_admin_dashboard_renders_with_stats(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'is_active' => true]);
        $coach = User::factory()->create(['role' => 'pelatih', 'is_active' => true]);
        $parent = $this->makeParent();

        $student = $this->makeStudent($parent, 'Ahmad Fauzi');

        $program = Program::create([
            'name' => 'Reguler',
            'slug' => 'reguler',
            'total_sessions' => 8,
            'price' => 350000,
            'billing_type' => 'per_paket',
            'is_active' => true,
        ]);

        $class = SchoolClass::create([
            'program_id' => $program->id,
            'name' => 'Reguler A',
            'capacity' => 4,
            'is_active' => true,
        ]);

        \DB::table('class_student')->insert([
            'class_id' => $class->id,
            'student_id' => $student->id,
            'sessions_completed' => 7,
            'is_active' => true,
            'renewal_status' => 'belum_konfirmasi',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->actingAs($admin)->get('/admin/dashboard');

        $response->assertOk();
        $response->assertSee('Selamat datang kembali');
        $response->assertSee('Total Siswa Aktif');
        $response->assertSee('Total Pelatih');
        $response->assertSee('Pendaftaran Menunggu');
        $response->assertSee('Paket Perlu Konfirmasi');
        $response->assertSee('Reguler A');
    }

    public function test_non_admin_cannot_access_admin_dashboard(): void
    {
        $parent = $this->makeParent();

        $response = $this->actingAs($parent)->get('/admin/dashboard');

        $response->assertForbidden();
    }
}
