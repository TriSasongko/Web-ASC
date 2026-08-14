<?php

namespace Database\Seeders;

use App\Models\BestTime;
use App\Models\ClassStudent;
use App\Models\Program;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;

class BestTimeSeeder extends Seeder
{
    // Data demo best time untuk atlet renang kelas Kompetitif.
    // ['gaya', jarak, waktu "MM:SS:mmm", hari yang lalu]
    private function data(): array
    {
        return [
            'Mutiara Hati' => [
                'class' => 'B',
                'gender' => 'P',
                'records' => [
                    ['bebas', 400, '07:12:40', 70],
                    ['bebas', 50, '00:33:45', 70],
                    ['bebas', 400, '07:02:15', 55],
                    ['dada', 100, '01:40:20', 55],
                    ['bebas', 400, '06:50:42', 30],
                    ['bebas', 50, '00:31:25', 30],
                    ['dada', 100, '01:35:40', 30],
                    ['bebas', 50, '00:32:10', 12],
                    ['kupu_kupu', 50, '00:34:12', 12],
                ],
            ],
            'Wildan Hakim' => [
                'class' => 'B',
                'gender' => 'L',
                'records' => [
                    ['bebas', 100, '01:12:55', 60],
                    ['punggung', 50, '00:41:20', 60],
                    ['bebas', 100, '01:08:40', 35],
                    ['bebas', 200, '02:30:10', 35],
                    ['bebas', 100, '01:05:80', 9],
                    ['bebas', 200, '02:24:15', 9],
                    ['punggung', 50, '00:38:90', 9],
                ],
            ],
            'Alya Ramadhani' => [
                'class' => 'B',
                'gender' => 'P',
                'records' => [
                    ['bebas', 200, '02:42:35', 60],
                    ['dada', 100, '01:44:50', 60],
                    ['bebas', 200, '02:35:10', 28],
                    ['bebas', 50, '00:33:05', 28],
                    ['kupu_kupu', 100, '01:48:30', 28],
                    ['bebas', 200, '02:30:55', 15],
                    ['bebas', 50, '00:32:40', 15],
                    ['dada', 100, '01:35:02', 15],
                    ['kupu_kupu', 100, '01:40:21', 15],
                    ['bebas', 50, '00:33:70', 6],
                ],
            ],
            'Satrio Wibowo' => [
                'class' => 'B',
                'gender' => 'L',
                'records' => [
                    ['bebas', 100, '01:18:30', 45],
                    ['bebas', 25, '00:15:70', 45],
                    ['bebas', 100, '01:12:44', 20],
                    ['bebas', 25, '00:14:98', 20],
                    ['dada', 50, '00:45:30', 20],
                    ['dada', 50, '00:42:60', 6],
                ],
            ],
            'Dinda Permata' => [
                'class' => 'A',
                'gender' => 'P',
                'records' => [
                    ['bebas', 400, '06:30:10', 80],
                    ['bebas', 50, '00:33:00', 80],
                    ['bebas', 400, '06:20:45', 50],
                    ['punggung', 100, '01:30:05', 50],
                    ['bebas', 400, '06:12:35', 25],
                    ['bebas', 50, '00:31:20', 25],
                    ['punggung', 100, '01:25:40', 25],
                    ['bebas', 50, '00:29:87', 18],
                    ['kupu_kupu', 50, '00:31:05', 18],
                    ['bebas', 50, '00:30:15', 4],
                ],
            ],
            'Evan Kurniawan' => [
                'class' => 'A',
                'gender' => 'L',
                'records' => [
                    ['bebas', 200, '03:05:30', 55],
                    ['dada', 100, '01:58:20', 55],
                    ['bebas', 200, '02:58:10', 30],
                    ['bebas', 100, '01:24:40', 30],
                    ['bebas', 200, '02:48:90', 10],
                    ['bebas', 100, '01:18:25', 10],
                    ['dada', 100, '01:45:00', 10],
                ],
            ],
        ];
    }

    public function run(): void
    {
        $admin = User::where('email', 'admin@asc.test')->first()
            ?? User::where('role', 'admin')->first();

        if ($admin === null) {
            $this->command->warn('BestTimeSeeder dilewati: belum ada akun admin.');

            return;
        }

        $program = Program::firstOrCreate(
            ['slug' => 'kompetitif'],
            [
                'name' => 'Kompetitif',
                'total_sessions' => null,
                'price' => 500000,
                'billing_type' => 'per_bulan',
                'is_kompetitif' => true,
                'is_active' => true,
            ]
        );

        $classes = [
            'A' => SchoolClass::firstOrCreate(
                ['program_id' => $program->id, 'name' => 'Kompetitif A'],
                ['level' => 3, 'is_active' => true]
            ),
            'B' => SchoolClass::firstOrCreate(
                ['program_id' => $program->id, 'name' => 'Kompetitif B'],
                ['level' => 2, 'is_active' => true]
            ),
        ];

        $parents = [];

        foreach ($this->data() as $name => $config) {
            $student = Student::where('full_name', $name)->first();

            if ($student === null) {
                $parent = $parents[$config['gender']] ??= User::create([
                    'name' => $config['gender'] === 'P' ? 'Ibu Best Time' : 'Bapak Best Time',
                    'email' => $config['gender'] === 'P' ? 'ibubesttime@asc.test' : 'bapakbesttime@asc.test',
                    'password' => 'password',
                    'role' => 'orang_tua',
                    'phone' => '08'.random_int(100000000, 999999999),
                    'is_active' => true,
                    'email_verified_at' => now(),
                ]);

                $student = Student::create([
                    'parent_id' => $parent->id,
                    'full_name' => $name,
                    'nickname' => strtok($name, ' '),
                    'gender' => $config['gender'],
                    'birth_date' => '2014-05-10',
                ]);
            }

            $class = $classes[$config['class']];

            ClassStudent::firstOrCreate(
                [
                    'class_id' => $class->id,
                    'student_id' => $student->id,
                    'is_active' => true,
                ],
                [
                    'level' => $class->level,
                    'sessions_completed' => 2,
                    'renewal_status' => 'aktif',
                    'started_at' => now()->subWeeks(8),
                    'ended_at' => null,
                ]
            );

            if (BestTime::where('student_id', $student->id)->exists()) {
                continue;
            }

            foreach ($config['records'] as [$style, $distance, $raw, $daysAgo]) {
                BestTime::create([
                    'student_id' => $student->id,
                    'class_id' => $class->id,
                    'recorded_by' => $admin->id,
                    'style' => $style,
                    'distance' => $distance,
                    'time_ms' => BestTime::parseTime($raw),
                    'recorded_at' => now()->subDays($daysAgo),
                ]);
            }
        }

        $this->command->info('Best time demo selesai di-seed.');
    }
}
