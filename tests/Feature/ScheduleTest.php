<?php

namespace Tests\Feature;

use App\Models\ClassSchedule;
use App\Models\ClassStudent;
use App\Models\Program;
use App\Models\Registration;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ScheduleTest extends TestCase
{
    use RefreshDatabase;

    private function makeAdmin(): User
    {
        return User::factory()->create(['role' => 'admin', 'is_active' => true]);
    }

    private function makeCoach(string $name = 'Coach A'): User
    {
        return User::factory()->create(['role' => 'pelatih', 'is_active' => true, 'name' => $name]);
    }

    private function makeParent(): User
    {
        return User::factory()->create(['role' => 'orang_tua', 'is_active' => true]);
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

    private function makeClass(): array
    {
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
            'capacity' => 10,
            'is_active' => true,
        ]);

        return compact('program', 'class');
    }

    private function makeSchedule(SchoolClass $class, array $overrides = []): ClassSchedule
    {
        return ClassSchedule::create(array_merge([
            'class_id' => $class->id,
            'day' => 'senin',
            'start_time' => '15:00',
            'end_time' => '16:00',
            'location' => 'Lapangan A',
            'session_number' => 1,
        ], $overrides));
    }

    public function test_admin_can_view_schedule_page()
    {
        $data = $this->makeClass();
        $this->makeSchedule($data['class']);

        $this->actingAs($this->makeAdmin())
            ->get(route('admin.schedules.index'))
            ->assertOk()
            ->assertSee('Jadwal Latihan');
    }

    public function test_admin_can_create_schedule_with_multiple_coaches()
    {
        $data = $this->makeClass();
        $coachA = $this->makeCoach('Coach A');
        $coachB = $this->makeCoach('Coach B');

        $this->actingAs($this->makeAdmin())
            ->post(route('admin.schedules.store'), [
                'class_id' => $data['class']->id,
                'day' => 'sabtu',
                'start_time' => '07:00',
                'end_time' => '09:00',
                'location' => 'Lapangan Utama',
                'session_number' => 2,
                'coach_ids' => [$coachA->id, $coachB->id],
            ])
            ->assertRedirect();

        $schedule = ClassSchedule::where('class_id', $data['class']->id)->first();

        $this->assertSame('sabtu', $schedule->day);
        $this->assertSame(2, $schedule->coaches()->count());
        $this->assertTrue($schedule->coaches()->whereKey($coachA->id)->exists());
        $this->assertTrue($schedule->coaches()->whereKey($coachB->id)->exists());
    }

    public function test_admin_can_create_schedule_with_selected_students_of_class()
    {
        $data = $this->makeClass();
        $parent = $this->makeParent();
        $studentA = $this->makeStudent($parent, 'Anak A');
        $studentB = $this->makeStudent($parent, 'Anak B');
        $foreignParent = $this->makeParent();
        $foreignStudent = $this->makeStudent($foreignParent, 'Anak Kelas Lain');

        foreach ([$studentA, $studentB] as $student) {
            ClassStudent::create([
                'class_id' => $data['class']->id,
                'student_id' => $student->id,
                'sessions_completed' => 0,
                'is_active' => true,
                'renewal_status' => 'belum_konfirmasi',
            ]);
        }
        ClassStudent::create([
            'class_id' => $data['class']->id,
            'student_id' => $foreignStudent->id,
            'sessions_completed' => 0,
            'is_active' => false,
            'renewal_status' => 'berhenti',
        ]);

        $this->actingAs($this->makeAdmin())
            ->post(route('admin.schedules.store'), [
                'class_id' => $data['class']->id,
                'day' => 'sabtu',
                'start_time' => '07:00',
                'end_time' => '09:00',
                'session_number' => 1,
                'student_ids' => [$studentA->id, $studentB->id, $foreignStudent->id],
            ])
            ->assertRedirect();

        $schedule = ClassSchedule::where('class_id', $data['class']->id)->first();

        $this->assertSame(2, $schedule->students()->count());
        $this->assertTrue($schedule->students()->whereKey($studentA->id)->exists());
        $this->assertTrue($schedule->students()->whereKey($studentB->id)->exists());
        $this->assertFalse($schedule->students()->whereKey($foreignStudent->id)->exists());
    }

    public function test_admin_can_assign_students_and_coaches_to_schedule()
    {
        $data = $this->makeClass();
        $coach = $this->makeCoach();
        $parent = $this->makeParent();
        $studentA = $this->makeStudent($parent, 'Anak A');
        $studentB = $this->makeStudent($parent, 'Anak B');
        $schedule = $this->makeSchedule($data['class']);

        ClassStudent::create([
            'class_id' => $data['class']->id,
            'student_id' => $studentA->id,
            'sessions_completed' => 0,
            'is_active' => true,
            'renewal_status' => 'belum_konfirmasi',
        ]);
        ClassStudent::create([
            'class_id' => $data['class']->id,
            'student_id' => $studentB->id,
            'sessions_completed' => 0,
            'is_active' => true,
            'renewal_status' => 'belum_konfirmasi',
        ]);

        $this->actingAs($this->makeAdmin())
            ->put(route('admin.schedules.assign', $schedule), [
                'student_ids' => [$studentA->id, $studentB->id],
                'coach_ids' => [$coach->id],
            ])
            ->assertRedirect();

        $this->assertSame(2, $schedule->students()->count());
        $this->assertTrue($schedule->students()->whereKey($studentA->id)->exists());
        $this->assertSame(1, $schedule->coaches()->count());
    }

    public function test_coach_only_sees_their_own_schedules()
    {
        $data = $this->makeClass();
        $coach = $this->makeCoach();
        $otherCoach = $this->makeCoach('Coach Lain');

        $mySchedule = $this->makeSchedule($data['class'], ['day' => 'senin', 'session_number' => 1]);
        $otherSchedule = $this->makeSchedule($data['class'], ['day' => 'selasa', 'session_number' => 2]);

        $mySchedule->coaches()->attach($coach->id);
        $otherSchedule->coaches()->attach($otherCoach->id);

        $this->actingAs($coach)
            ->get(route('pelatih.schedules.index'))
            ->assertOk()
            ->assertSee('Jadwal Latihan Saya');

        $this->assertTrue($mySchedule->coaches()->whereKey($coach->id)->exists());
        $this->assertFalse($otherSchedule->coaches()->whereKey($coach->id)->exists());
    }

    public function test_placing_student_can_attach_to_multiple_schedules_of_chosen_class()
    {
        $data = $this->makeClass();
        $parent = $this->makeParent();
        $student = $this->makeStudent($parent);

        $scheduleA = $this->makeSchedule($data['class'], ['day' => 'senin', 'session_number' => 1]);
        $scheduleB = $this->makeSchedule($data['class'], ['day' => 'rabu', 'session_number' => 2]);

        $registration = Registration::create([
            'student_id' => $student->id,
            'program_id' => $data['program']->id,
            'status' => 'diterima',
        ]);

        $this->actingAs($this->makeAdmin())
            ->post(route('admin.class-students.place', $registration), [
                'class_id' => $data['class']->id,
                'schedule_ids' => [$scheduleA->id, $scheduleB->id],
            ])
            ->assertRedirect();

        $this->assertTrue($student->schedules()->whereKey($scheduleA->id)->exists());
        $this->assertTrue($student->schedules()->whereKey($scheduleB->id)->exists());
        $this->assertDatabaseHas('class_student', [
            'class_id' => $data['class']->id,
            'student_id' => $student->id,
            'is_active' => true,
        ]);
    }

    public function test_placing_student_ignores_schedules_from_other_class()
    {
        $data = $this->makeClass();
        $parent = $this->makeParent();
        $student = $this->makeStudent($parent);

        $otherClass = SchoolClass::create([
            'program_id' => $data['program']->id,
            'name' => 'Reguler B',
            'level' => 1,
            'is_active' => true,
        ]);
        $foreignSchedule = $this->makeSchedule($otherClass, ['session_number' => 1]);

        $registration = Registration::create([
            'student_id' => $student->id,
            'program_id' => $data['program']->id,
            'status' => 'diterima',
        ]);

        $this->actingAs($this->makeAdmin())
            ->post(route('admin.class-students.place', $registration), [
                'class_id' => $data['class']->id,
                'schedule_ids' => [$foreignSchedule->id],
            ])
            ->assertRedirect();

        $this->assertFalse($student->schedules()->whereKey($foreignSchedule->id)->exists());
    }
}
