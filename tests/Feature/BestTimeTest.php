<?php

namespace Tests\Feature;

use App\Models\BestTime;
use App\Models\ClassStudent;
use App\Models\Program;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BestTimeTest extends TestCase
{
    use RefreshDatabase;

    private function makeAdmin(): User
    {
        return User::factory()->create(['role' => 'admin', 'is_active' => true]);
    }

    private function makeData(bool $kompetitif = true): array
    {
        $admin = $this->makeAdmin();
        $parent = User::factory()->create(['role' => 'orang_tua', 'is_active' => true]);

        $student = Student::create([
            'parent_id' => $parent->id,
            'full_name' => 'Atlet Kompetitif',
            'birth_date' => '2012-01-01',
            'gender' => 'L',
        ]);

        $program = Program::firstOrCreate([
            'slug' => $kompetitif ? 'kompetitif' : 'reguler',
        ], [
            'name' => $kompetitif ? 'Kompetitif' : 'Reguler',
            'total_sessions' => $kompetitif ? null : 8,
            'price' => $kompetitif ? 500000 : 350000,
            'billing_type' => $kompetitif ? 'per_bulan' : 'per_paket',
            'is_kompetitif' => $kompetitif,
            'is_active' => true,
        ]);

        $class = SchoolClass::create([
            'program_id' => $program->id,
            'name' => $kompetitif ? 'Kompetitif A' : 'Reguler A',
            'level' => $kompetitif ? 2 : 1,
            'is_active' => true,
        ]);

        ClassStudent::create([
            'class_id' => $class->id,
            'student_id' => $student->id,
            'sessions_completed' => 1,
            'is_active' => true,
            'renewal_status' => 'aktif',
        ]);

        return [$admin, $class, $student];
    }

    public function test_admin_can_access_best_time_pages(): void
    {
        [$admin, $class, $student] = $this->makeData();

        $this->actingAs($admin)
            ->get(route('admin.best-times.index'))
            ->assertOk();

        $this->actingAs($admin)
            ->get(route('admin.classes.best-times.index', $class))
            ->assertOk();

        $this->actingAs($admin)
            ->get(route('admin.classes.best-times.create', [$class, $student]))
            ->assertOk();

        $this->actingAs($admin)
            ->get(route('admin.classes.best-times.history', [$class, $student]))
            ->assertOk();
    }

    public function test_store_creates_records_and_parses_time(): void
    {
        [$admin, $class, $student] = $this->makeData();

        $this->actingAs($admin)
            ->post(route('admin.classes.best-times.store', [$class, $student]), [
                'recorded_at' => '2026-08-10',
                'times' => [
                    'bebas' => ['400' => '06:50:42', '100' => ''],
                    'dada' => ['100' => '01:25:37'],
                ],
            ])
            ->assertRedirect(route('admin.classes.best-times.history', [$class, $student]));

        $this->assertDatabaseHas('best_times', [
            'student_id' => $student->id,
            'style' => 'bebas',
            'distance' => 400,
            'time_ms' => 6 * 60000 + 50 * 1000 + 42,
            'recorded_by' => $admin->id,
        ]);

        $this->assertDatabaseHas('best_times', [
            'student_id' => $student->id,
            'style' => 'dada',
            'distance' => 100,
            'time_ms' => 85037,
        ]);

        $this->assertSame(2, BestTime::where('student_id', $student->id)->count());
    }

    public function test_invalid_time_format_is_rejected(): void
    {
        [$admin, $class, $student] = $this->makeData();

        $this->actingAs($admin)
            ->post(route('admin.classes.best-times.store', [$class, $student]), [
                'recorded_at' => '2026-08-10',
                'times' => [
                    'bebas' => ['100' => '95:xx:yy'],
                ],
            ])
            ->assertSessionHasErrors('times.bebas.100');

        $this->assertSame(0, BestTime::where('student_id', $student->id)->count());
    }

    public function test_empty_submission_is_rejected(): void
    {
        [$admin, $class, $student] = $this->makeData();

        $this->actingAs($admin)
            ->post(route('admin.classes.best-times.store', [$class, $student]), [
                'recorded_at' => '2026-08-10',
                'times' => ['bebas' => ['50' => '']],
            ])
            ->assertSessionHasErrors('times');

        $this->assertSame(0, BestTime::where('student_id', $student->id)->count());
    }

    public function test_best_time_highlights_fastest_record(): void
    {
        [$admin, $class, $student] = $this->makeData();

        foreach ([['00:31:20', '2026-07-20'], ['00:29:87', '2026-08-10'], ['00:35:00', '2026-08-12']] as [$raw, $date]) {
            BestTime::create([
                'student_id' => $student->id,
                'class_id' => $class->id,
                'recorded_by' => $admin->id,
                'style' => 'bebas',
                'distance' => 50,
                'time_ms' => BestTime::parseTime($raw),
                'recorded_at' => $date,
            ]);
        }

        $this->actingAs($admin)
            ->get(route('admin.classes.best-times.history', [$class, $student]))
            ->assertOk()
            ->assertViewHas('best', function ($best) {
                return $best['bebas'][50] === BestTime::parseTime('00:29:87');
            })
            ->assertViewHas('records', function ($records) {
                return $records->count() === 3;
            })
            ->assertViewHas('recordsByDate', function ($recordsByDate) {
                return $recordsByDate->keys()->contains('2026-08-12')
                    && $recordsByDate->get('2026-08-12')->count() === 1;
            });

        $this->actingAs($student->parent)
            ->get(route('orangtua.best-times.index'))
            ->assertOk()
            ->assertViewHas('best', function ($best) use ($student) {
                return $best[$student->id]['bebas'][50] === BestTime::parseTime('00:29:87');
            });
    }

    public function test_history_limits_entries_per_date(): void
    {
        [$admin, $class, $student] = $this->makeData();

        for ($i = 1; $i <= 12; $i++) {
            BestTime::create([
                'student_id' => $student->id,
                'class_id' => $class->id,
                'recorded_by' => $admin->id,
                'style' => 'bebas',
                'distance' => 50,
                'time_ms' => BestTime::parseTime(sprintf('00:%02d:%02d', $i, $i)),
                'recorded_at' => '2026-08-14',
            ]);
        }

        $this->actingAs($admin)
            ->get(route('admin.classes.best-times.history', [$class, $student]))
            ->assertOk()
            ->assertViewHas('recordsByDate', function ($recordsByDate) {
                return $recordsByDate->get('2026-08-14')->count() === 12;
            })
            ->assertSee('Lihat 2 catatan lainnya', false);
    }

    public function test_history_does_not_show_more_button_when_few_entries(): void
    {
        [$admin, $class, $student] = $this->makeData();

        for ($i = 1; $i <= 4; $i++) {
            BestTime::create([
                'student_id' => $student->id,
                'class_id' => $class->id,
                'recorded_by' => $admin->id,
                'style' => 'bebas',
                'distance' => 50,
                'time_ms' => BestTime::parseTime(sprintf('00:%02d:%02d', $i, $i)),
                'recorded_at' => '2026-08-14',
            ]);
        }

        $this->actingAs($admin)
            ->get(route('admin.classes.best-times.history', [$class, $student]))
            ->assertOk()
            ->assertDontSee('catatan lainnya', false);
    }

    public function test_destroy_many_deletes_only_selected_records_of_student(): void
    {
        [$admin, $class, $student] = $this->makeData();

        $ids = [];
        for ($i = 1; $i <= 3; $i++) {
            $ids[] = BestTime::create([
                'student_id' => $student->id,
                'class_id' => $class->id,
                'recorded_by' => $admin->id,
                'style' => 'bebas',
                'distance' => 50,
                'time_ms' => BestTime::parseTime(sprintf('00:%02d:%02d', $i, $i)),
                'recorded_at' => '2026-08-14',
            ])->id;
        }

        $otherParent = User::factory()->create(['role' => 'orang_tua', 'is_active' => true]);
        $otherStudent = Student::create([
            'parent_id' => $otherParent->id,
            'full_name' => 'Atlet Lain',
            'birth_date' => '2012-01-01',
            'gender' => 'P',
        ]);
        ClassStudent::create([
            'class_id' => $class->id,
            'student_id' => $otherStudent->id,
            'sessions_completed' => 1,
            'is_active' => true,
            'renewal_status' => 'aktif',
        ]);
        $otherRecord = BestTime::create([
            'student_id' => $otherStudent->id,
            'class_id' => $class->id,
            'recorded_by' => $admin->id,
            'style' => 'bebas',
            'distance' => 100,
            'time_ms' => BestTime::parseTime('01:05:00'),
            'recorded_at' => '2026-08-14',
        ]);

        $this->actingAs($admin)
            ->post(route('admin.classes.best-times.destroy-many', [$class, $student]), [
                'ids' => [$ids[0], $ids[2], $otherRecord->id],
            ])
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertSame(1, BestTime::where('student_id', $student->id)->count());
        $this->assertDatabaseMissing('best_times', ['id' => $ids[0]]);
        $this->assertDatabaseMissing('best_times', ['id' => $ids[2]]);
        $this->assertDatabaseHas('best_times', ['id' => $otherRecord->id]);
    }

    public function test_destroy_many_requires_ids(): void
    {
        [$admin, $class, $student] = $this->makeData();

        $this->actingAs($admin)
            ->post(route('admin.classes.best-times.destroy-many', [$class, $student]), [
                'ids' => [],
            ])
            ->assertSessionHasErrors('ids');
    }

    public function test_non_kompetitif_class_is_forbidden(): void
    {
        [$admin, $class, $student] = $this->makeData(false);

        $this->actingAs($admin)
            ->get(route('admin.classes.best-times.index', $class))
            ->assertForbidden();

        $this->actingAs($admin)
            ->get(route('admin.classes.best-times.create', [$class, $student]))
            ->assertForbidden();

        $this->actingAs($admin)
            ->post(route('admin.classes.best-times.store', [$class, $student]), [
                'recorded_at' => '2026-08-10',
                'times' => ['bebas' => ['50' => '00:30:00']],
            ])
            ->assertForbidden();
    }

    public function test_pelatih_cannot_access_admin_best_times(): void
    {
        [$admin, $class, $student] = $this->makeData();
        $coach = User::factory()->create(['role' => 'pelatih', 'is_active' => true]);

        $this->actingAs($coach)
            ->get(route('admin.best-times.index'))
            ->assertForbidden();
    }

    public function test_destroy_deletes_record(): void
    {
        [$admin, $class, $student] = $this->makeData();

        $record = BestTime::create([
            'student_id' => $student->id,
            'class_id' => $class->id,
            'recorded_by' => $admin->id,
            'style' => 'bebas',
            'distance' => 100,
            'time_ms' => BestTime::parseTime('01:10:00'),
            'recorded_at' => '2026-08-10',
        ]);

        $this->actingAs($admin)
            ->delete(route('admin.best-times.destroy', $record))
            ->assertRedirect();

        $this->assertDatabaseMissing('best_times', ['id' => $record->id]);
    }

    public function test_parent_sees_only_own_child_best_times(): void
    {
        [$admin, $class, $student] = $this->makeData();
        $otherParent = User::factory()->create(['role' => 'orang_tua', 'is_active' => true]);

        $otherStudent = Student::create([
            'parent_id' => $otherParent->id,
            'full_name' => 'Anak Lain',
            'birth_date' => '2012-01-01',
            'gender' => 'P',
        ]);

        BestTime::create([
            'student_id' => $student->id,
            'class_id' => $class->id,
            'recorded_by' => $admin->id,
            'style' => 'bebas',
            'distance' => 50,
            'time_ms' => BestTime::parseTime('00:30:00'),
            'recorded_at' => '2026-08-10',
        ]);

        $this->actingAs($student->parent)
            ->get(route('orangtua.best-times.index'))
            ->assertOk()
            ->assertSee($student->full_name)
            ->assertDontSee($otherStudent->full_name);

        $this->actingAs($otherParent)
            ->get(route('orangtua.best-times.index'))
            ->assertOk()
            ->assertSee($otherStudent->full_name)
            ->assertDontSee($student->full_name);
    }

    public function test_create_form_input_names_use_bracket_syntax(): void
    {
        [$admin, $class, $student] = $this->makeData();

        $response = $this->actingAs($admin)
            ->get(route('admin.classes.best-times.create', [$class, $student]));

        $response->assertOk();
        $response->assertSee('name="times[bebas][400]"', false);
        $response->assertSee('name="times[kupu_kupu][200]"', false);
        $response->assertDontSee('name="times.', false);
    }
}
