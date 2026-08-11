<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\Program;
use App\Models\Registration;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class StudentSeeder extends Seeder
{
    protected array $dayMap = [
        'senin' => Carbon::MONDAY,
        'selasa' => Carbon::TUESDAY,
        'rabu' => Carbon::WEDNESDAY,
        'kamis' => Carbon::THURSDAY,
        'jumat' => Carbon::FRIDAY,
        'sabtu' => Carbon::SATURDAY,
        'minggu' => Carbon::SUNDAY,
    ];

    public function run(): void
    {
        $admin = User::where('role', 'admin')->firstOrFail();
        // Paket per_paket: Reguler (8x), Mini Reguler (4x), Private (8x).
        // sessions_completed menentukan sisa paket: 7/8 & 3/4 → hampir habis, 4/8 & 2/4 → setengah jalan.
        $students = [
            [
                'full_name' => 'Ahmad Fauzi',
                'nickname' => 'Ahmad',
                'birth_place' => 'Bandung',
                'birth_date' => '2015-03-14',
                'gender' => 'L',
                'weight' => 38.50,
                'height' => 138.00,
                'address' => 'Jl. Merdeka No. 10, Bandung',
                'parent' => ['name' => 'Rudi Fauzi', 'email' => 'rudi.fauzi@asc.test', 'phone' => '081234567801'],
                'program' => 'reguler',
                'class_name' => 'Reguler A',
                'sessions_completed' => 7,
            ],
            [
                'full_name' => 'Budi Santoso',
                'nickname' => 'Budi',
                'birth_place' => 'Jakarta',
                'birth_date' => '2014-07-22',
                'gender' => 'L',
                'weight' => 42.00,
                'height' => 145.00,
                'address' => 'Jl. Sudirman No. 5, Jakarta',
                'parent' => ['name' => 'Slamet Santoso', 'email' => 'slamet.santoso@asc.test', 'phone' => '081234567802'],
                'program' => 'reguler',
                'class_name' => 'Reguler A',
                'sessions_completed' => 4,
            ],
            [
                'full_name' => 'Citra Lestari',
                'nickname' => 'Citra',
                'birth_place' => 'Surabaya',
                'birth_date' => '2015-11-02',
                'gender' => 'P',
                'weight' => 35.00,
                'height' => 132.00,
                'address' => 'Jl. Pemuda No. 21, Surabaya',
                'parent' => ['name' => 'Bambang Lestari', 'email' => 'bambang.lestari@asc.test', 'phone' => '081234567803'],
                'program' => 'mini-reguler',
                'class_name' => 'Mini Reguler A',
                'sessions_completed' => 3,
            ],
            [
                'full_name' => 'Dewi Anggraini',
                'nickname' => 'Dewi',
                'birth_place' => 'Yogyakarta',
                'birth_date' => '2016-01-30',
                'gender' => 'P',
                'weight' => 30.50,
                'height' => 125.00,
                'address' => 'Jl. Malioboro No. 8, Yogyakarta',
                'parent' => ['name' => 'Siti Anggraini', 'email' => 'siti.anggraini@asc.test', 'phone' => '081234567804'],
                'program' => 'mini-reguler',
                'class_name' => 'Mini Reguler A',
                'sessions_completed' => 2,
            ],
            [
                'full_name' => 'Eko Prasetyo',
                'nickname' => 'Eko',
                'birth_place' => 'Semarang',
                'birth_date' => '2014-05-18',
                'gender' => 'L',
                'weight' => 45.00,
                'height' => 150.00,
                'address' => 'Jl. Pandanaran No. 12, Semarang',
                'parent' => ['name' => 'Agus Prasetyo', 'email' => 'agus.prasetyo@asc.test', 'phone' => '081234567805'],
                'program' => 'private',
                'class_name' => 'Private A',
                'sessions_completed' => 4,
            ],
        ];

        foreach ($students as $data) {
            $parent = User::firstOrCreate(
                ['email' => $data['parent']['email']],
                [
                    'name' => $data['parent']['name'],
                    'password' => Hash::make('password'),
                    'role' => 'orang_tua',
                    'phone' => $data['parent']['phone'],
                    'email_verified_at' => now(),
                ]
            );

            $student = Student::create([
                'parent_id' => $parent->id,
                'full_name' => $data['full_name'],
                'nickname' => $data['nickname'],
                'birth_place' => $data['birth_place'],
                'birth_date' => $data['birth_date'],
                'gender' => $data['gender'],
                'weight' => $data['weight'],
                'height' => $data['height'],
                'address' => $data['address'],
            ]);

            $program = Program::where('slug', $data['program'])->firstOrFail();

            $class = SchoolClass::firstOrCreate(
                ['name' => $data['class_name']],
                [
                    'program_id' => $program->id,
                    'is_active' => true,
                ]
            );

            $schedule = $class->schedules()->firstOrCreate(
                ['day' => 'sabtu', 'session_number' => 1],
                ['start_time' => '08:00:00', 'end_time' => '09:30:00', 'location' => 'Lapangan ASC']
            );

            $registration = Registration::firstOrCreate(
                ['student_id' => $student->id, 'program_id' => $program->id],
                [
                    'status' => 'diterima',
                    'verified_by' => $admin->id,
                    'verified_at' => now(),
                ]
            );

            DB::table('class_student')->updateOrInsert(
                [
                    'class_id' => $class->id,
                    'student_id' => $student->id,
                ],
                [
                    'registration_id' => $registration->id,
                    'sessions_completed' => $data['sessions_completed'],
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );

            $this->seedAttendance($student, $class, $schedule, $data['sessions_completed'], $admin);
        }
    }

    protected function seedAttendance(Student $student, SchoolClass $class, $schedule, int $completed, User $admin): void
    {
        $total = $class->program->total_sessions;
        $rows = min($completed, $total);

        $dates = $this->sessionDates($schedule->day, $rows);

        foreach ($dates as $date) {
            Attendance::firstOrCreate(
                [
                    'class_id' => $class->id,
                    'student_id' => $student->id,
                    'attendance_date' => $date,
                    'session_number' => $schedule->session_number,
                ],
                [
                    'recorded_by' => $admin->id,
                ]
            );
        }
    }

    protected function sessionDates(string $day, int $count): array
    {
        $target = $this->dayMap[$day];
        $cursor = Carbon::today();
        $dates = [];

        while (count($dates) < $count) {
            if ($cursor->dayOfWeek === $target) {
                $dates[] = $cursor->copy();
            }
            $cursor->subDay();
        }

        return array_reverse($dates);
    }
}
