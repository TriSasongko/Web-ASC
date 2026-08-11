<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\ClassRecommendation;
use App\Models\ClassSchedule;
use App\Models\ClassStudent;
use App\Models\Development;
use App\Models\Program;
use App\Models\Registration;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

class DummyDataSeeder extends Seeder
{
    private array $places = ['Jakarta', 'Bandung', 'Surabaya', 'Yogyakarta', 'Semarang', 'Bekasi', 'Depok', 'Tangerang'];

    private array $locations = ['Kolam Renang ASC Senayan', 'Kolam Renang ASC Cibubur', 'Kolam Renang ASC Bekasi'];

    private array $addresses = [
        'Jl. Melati No. 12, Jakarta Selatan',
        'Jl. Kenanga No. 45, Bandung',
        'Jl. Anggrek No. 7, Surabaya',
        'Jl. Mawar No. 88, Bekasi',
        'Jl. Tulip No. 3, Depok',
        'Jl. Cempaka No. 21, Tangerang',
    ];

    private array $coachNotes = [
        'Pertumbuhan latihan sangat baik, terus pertahankan.',
        'Perlu peningkatan pada koordinasi gerakan tangan.',
        'Sudah mulai percaya diri di dalam air.',
        'Teknik napas perlu diperbaiki lagi.',
        'Anak aktif dan antusias mengikuti setiap latihan.',
        'Disiplin latihan sudah bagus, tinggal konsistensi gerakan.',
    ];

    public function run(): void
    {
        mt_srand(1234);

        $admin = User::where('email', 'admin@asc.test')->first();
        $demoCoach = User::where('email', 'pelatih@asc.test')->first();
        $demoParent = User::where('email', 'ortu@asc.test')->first();

        $coaches = $this->createCoaches($demoCoach);
        $parents = $this->createParents($demoParent);
        $students = $this->createStudents($parents);
        $classes = $this->createClasses();
        $schedules = $this->createSchedules($classes);

        $studentsByName = $students->keyBy('full_name');

        foreach ($students as $student) {
            $this->createRegistration($student, $admin);
        }

        foreach ($students as $student) {
            $data = $this->studentData()[$student->full_name];

            if ($data['class'] !== null) {
                $this->createEnrollment($student, $classes, $data, $coaches);
            }
        }

        $this->addKiranaTransfer($studentsByName, $classes, $coaches);
        $this->addTrialAttendances($studentsByName, $coaches);
        $this->assignSchedules($classes, $schedules, $coaches);
        $this->createRecommendations($studentsByName, $classes, $coaches, $admin);
    }

    private function createCoaches(User $demoCoach): Collection
    {
        $coaches = collect([$demoCoach]);

        foreach (['Andi Wijaya', 'Budi Santoso', 'Citra Lestari', 'Dedi Kurniawan', 'Eka Prasetyo'] as $i => $name) {
            $coaches->push(User::create([
                'name' => $name,
                'email' => 'coach'.($i + 1).'@asc.test',
                'password' => 'password',
                'role' => 'pelatih',
                'phone' => '08'.mt_rand(100000000, 999999999),
                'address' => $this->addresses[array_rand($this->addresses)],
                'is_active' => true,
                'email_verified_at' => now(),
            ]));
        }

        return $coaches;
    }

    private function createParents(User $demoParent): Collection
    {
        $parents = collect([$demoParent]);

        foreach (['Bambang Sutrisno', 'Ratih Wulandari', 'Hendra Gunawan', 'Lina Marlina', 'Joko Susilo', 'Maya Puspita', 'Agus Salim', 'Rina Wati', 'Dedi Mulyadi', 'Nia Kurniasih', 'Tono Hartono', 'Fitri Handayani', 'Yanto Wijaya', 'Sri Rahayu'] as $i => $name) {
            $parents->push(User::create([
                'name' => $name,
                'email' => 'parent'.($i + 1).'@asc.test',
                'password' => 'password',
                'role' => 'orang_tua',
                'phone' => '08'.mt_rand(100000000, 999999999),
                'address' => $this->addresses[array_rand($this->addresses)],
                'is_active' => true,
                'email_verified_at' => now(),
            ]));
        }

        return $parents;
    }

    private function createStudents(Collection $parents): Collection
    {
        $students = collect();

        foreach (array_values($this->studentData()) as $i => $data) {
            $students->push(Student::create([
                'parent_id' => $parents[$i % $parents->count()]->id,
                'full_name' => $data['name'],
                'nickname' => strtok($data['name'], ' '),
                'birth_place' => $this->places[array_rand($this->places)],
                'birth_date' => date('Y-m-d', mt_rand(strtotime('2011-01-01'), strtotime('2019-12-31'))),
                'gender' => $data['gender'],
                'weight' => mt_rand(2200, 6500) / 100,
                'height' => mt_rand(10500, 17000) / 100,
                'address' => $this->addresses[array_rand($this->addresses)],
            ]));
        }

        return $students;
    }

    private function createClasses(): array
    {
        $classes = [];
        $configs = [
            ['name' => 'Private', 'program' => 'private', 'level' => 1],
            ['name' => 'Mini Private', 'program' => 'mini-private', 'level' => 1],
            ['name' => 'Reguler', 'program' => 'reguler', 'level' => 1],
            ['name' => 'Mini Reguler', 'program' => 'mini-reguler', 'level' => 1],
            ['name' => 'Kompetitif A', 'program' => 'kompetitif', 'level' => 3],
            ['name' => 'Kompetitif B', 'program' => 'kompetitif', 'level' => 2],
        ];

        foreach ($configs as $config) {
            $classes[$config['name']] = SchoolClass::create([
                'program_id' => Program::where('slug', $config['program'])->first()->id,
                'name' => $config['name'],
                'level' => $config['level'],
                'is_active' => true,
            ]);
        }

        return $classes;
    }

    private function createSchedules(array $classes): array
    {
        $scheduleConfig = [
            'Private' => [['senin', '15:00', '16:00', 1]],
            'Mini Private' => [['selasa', '15:00', '16:00', 1]],
            'Reguler' => [['rabu', '15:00', '16:30', 1]],
            'Mini Reguler' => [['jumat', '15:00', '16:30', 1]],
            'Kompetitif A' => [['sabtu', '07:00', '09:00', 1]],
            'Kompetitif B' => [['minggu', '07:00', '09:00', 1]],
        ];

        $created = [];

        foreach ($scheduleConfig as $className => $items) {
            foreach ($items as [$day, $start, $end, $session]) {
                $created[$className][] = ClassSchedule::create([
                    'class_id' => $classes[$className]->id,
                    'day' => $day,
                    'start_time' => $start,
                    'end_time' => $end,
                    'location' => $this->locations[array_rand($this->locations)],
                    'session_number' => $session,
                ]);
            }
        }

        return $created;
    }

    private function assignSchedules(array $classes, array $schedules, Collection $coaches): void
    {
        foreach ($schedules as $className => $items) {
            $studentIds = $classes[$className]->students()->pluck('students.id');
            $assignedCoaches = $coaches->random(mt_rand(1, min(2, $coaches->count())));

            foreach ($items as $schedule) {
                if ($studentIds->isNotEmpty()) {
                    $schedule->students()->sync($studentIds);
                }
                $schedule->coaches()->sync($assignedCoaches->pluck('id'));
            }
        }
    }

    private function createRegistration(Student $student, User $admin): void
    {
        $data = $this->studentData()[$student->full_name];
        $program = Program::where('slug', $data['program'])->first();

        Registration::create([
            'student_id' => $student->id,
            'program_id' => $program->id,
            'status' => $data['status'],
            'rejection_reason' => $data['status'] === 'ditolak' ? $data['rejection_reason'] : null,
            'verified_by' => in_array($data['status'], ['diterima', 'ditolak']) ? $admin->id : null,
            'verified_at' => in_array($data['status'], ['diterima', 'ditolak']) ? now()->subDays(mt_rand(1, 30)) : null,
        ]);
    }

    private function createEnrollment(Student $student, array $classes, array $data, Collection $coaches): void
    {
        $enrollment = ClassStudent::create([
            'class_id' => $classes[$data['class']]->id,
            'student_id' => $student->id,
            'level' => $data['level'] ?? null,
            'registration_id' => $student->registrations()->first()?->id,
            'sessions_completed' => $data['completed'],
            'is_active' => $data['is_active'],
            'renewal_status' => $data['renewal'],
            'renewal_note' => $this->renewalNote($data['renewal']),
            'renewed_at' => in_array($data['renewal'], ['lanjut', 'berhenti', 'pindah']) ? now()->subDays(mt_rand(1, 15)) : null,
            'started_at' => now()->subDays(mt_rand(30, 120)),
        ]);

        $this->createAttendances($student, $classes[$data['class']], $data['completed'], $coaches);
        $this->createDevelopment($student, $classes[$data['class']], $coaches, $data['completed']);
    }

    private function createAttendances(Student $student, SchoolClass $class, int $count, Collection $coaches): void
    {
        if ($count <= 0) {
            return;
        }

        $start = now()->subDays(7 * $count);
        $recorder = $coaches->random();
        $location = $this->locations[array_rand($this->locations)];

        for ($i = 1; $i <= $count; $i++) {
            Attendance::create([
                'class_id' => $class->id,
                'student_id' => $student->id,
                'recorded_by' => $recorder->id,
                'attendance_date' => $start->copy()->addDays(7 * $i),
                'session_number' => $i,
                'location' => $location,
            ]);
        }
    }

    private function createDevelopment(Student $student, SchoolClass $class, Collection $coaches, int $completed): void
    {
        $coach = $coaches->random();
        $periods = ['Agustus 2026'];

        if ($completed >= 6) {
            array_unshift($periods, 'Juli 2026');
        }

        foreach ($periods as $period) {
            $aspects = [];

            foreach (array_keys(Development::aspects()) as $key) {
                $aspects[$key] = $this->randomScore();
            }

            Development::create([
                'class_id' => $class->id,
                'student_id' => $student->id,
                'coach_id' => $coach->id,
                'period' => $period,
                'coach_note' => $this->coachNotes[array_rand($this->coachNotes)],
                ...$aspects,
            ]);
        }
    }

    private function addKiranaTransfer(Collection $studentsByName, array $classes, Collection $coaches): void
    {
        $kirana = $studentsByName['Kirana Ayu'];

        ClassStudent::create([
            'class_id' => $classes['Kompetitif A']->id,
            'student_id' => $kirana->id,
            'level' => 3,
            'registration_id' => null,
            'sessions_completed' => 1,
            'is_active' => true,
            'renewal_status' => 'lanjut',
            'renewal_note' => 'Lanjut setelah pindah level',
            'renewed_at' => now(),
            'started_at' => now()->subDays(30),
        ]);

        Attendance::create([
            'class_id' => $classes['Kompetitif A']->id,
            'student_id' => $kirana->id,
            'recorded_by' => $coaches->random()->id,
            'attendance_date' => now()->subDays(2),
            'session_number' => 1,
            'location' => $this->locations[array_rand($this->locations)],
        ]);

        $this->createDevelopment($kirana, $classes['Kompetitif A'], $coaches, 1);
    }

    private function addTrialAttendances(Collection $studentsByName, Collection $coaches): void
    {
        foreach (['Zahra Amalia', 'Alya Ramadhani'] as $name) {
            $student = $studentsByName[$name];

            Attendance::create([
                'class_id' => null,
                'student_id' => $student->id,
                'recorded_by' => $coaches->random()->id,
                'attendance_date' => now()->subDays(mt_rand(40, 60)),
                'session_number' => 99,
                'location' => $this->locations[array_rand($this->locations)],
            ]);
        }
    }

    private function createRecommendations(Collection $studentsByName, array $classes, Collection $coaches, User $admin): void
    {
        $recommendations = [
            ['name' => 'Fajar Ramadhan', 'current' => 'Reguler', 'target' => 'Kompetitif B', 'level' => 2, 'status' => 'pending'],
            ['name' => 'Ahmad Fauzi', 'current' => 'Private', 'target' => 'Kompetitif B', 'level' => 2, 'status' => 'pending'],
            ['name' => 'Bella Septiana', 'current' => 'Mini Reguler', 'target' => 'Kompetitif B', 'level' => 2, 'status' => 'pending'],
            ['name' => 'Mutiara Hati', 'current' => 'Kompetitif B', 'target' => 'Kompetitif A', 'level' => 3, 'status' => 'pending'],
            ['name' => 'Kirana Ayu', 'current' => 'Mini Reguler', 'target' => 'Kompetitif A', 'level' => 3, 'status' => 'diterima'],
            ['name' => 'Naila Safitri', 'current' => 'Reguler', 'target' => 'Kompetitif A', 'level' => 3, 'status' => 'ditolak'],
            ['name' => 'Bintang Mahesa', 'current' => 'Reguler', 'target' => 'Kompetitif B', 'level' => 2, 'status' => 'ditolak'],
        ];

        foreach ($recommendations as $rec) {
            $student = $studentsByName[$rec['name']];

            ClassRecommendation::create([
                'student_id' => $student->id,
                'from_user_id' => $coaches->random()->id,
                'current_class_id' => $classes[$rec['current']]->id,
                'recommended_class_id' => $classes[$rec['target']]->id,
                'recommended_level' => $rec['level'],
                'note' => $rec['status'] === 'ditolak'
                    ? 'Evaluasi teknik belum memenuhi standar kenaikan level.'
                    : 'Rekomendasi kenaikan level berdasarkan evaluasi perkembangan.',
                'status' => $rec['status'],
                'approved_by' => $rec['status'] === 'diterima' ? $admin->id : null,
                'moved_at' => $rec['status'] === 'diterima' ? now() : null,
            ]);
        }
    }

    private function randomScore(): string
    {
        return match (mt_rand(1, 10)) {
            1, 2 => 'kurang',
            3, 4 => 'cukup',
            5, 6, 7, 8 => 'baik',
            default => 'sangat_baik',
        };
    }

    private function renewalNote(?string $status): ?string
    {
        return match ($status) {
            'lanjut' => 'Lanjut ke paket berikutnya.',
            'berhenti' => 'Mengakhiri paket dan tidak melanjutkan.',
            'pindah' => 'Pindah kelas/level atas rekomendasi pelatih.',
            default => null,
        };
    }

    private function studentData(): array
    {
        return collect([
            ['name' => 'Ahmad Fauzi', 'gender' => 'L', 'program' => 'private', 'status' => 'diterima', 'class' => 'Private', 'level' => 1, 'completed' => 7, 'renewal' => 'lanjut', 'is_active' => true],
            ['name' => 'Salsabila Putri', 'gender' => 'P', 'program' => 'private', 'status' => 'diterima', 'class' => 'Private', 'level' => 1, 'completed' => 8, 'renewal' => 'lanjut', 'is_active' => true],
            ['name' => 'Raka Pratama', 'gender' => 'L', 'program' => 'private', 'status' => 'diterima', 'class' => 'Private', 'level' => 1, 'completed' => 5, 'renewal' => 'berhenti', 'is_active' => false],
            ['name' => 'Nadia Aulia', 'gender' => 'P', 'program' => 'mini-private', 'status' => 'diterima', 'class' => 'Mini Private', 'level' => 1, 'completed' => 3, 'renewal' => 'lanjut', 'is_active' => true],
            ['name' => 'Dimas Anggara', 'gender' => 'L', 'program' => 'mini-private', 'status' => 'diterima', 'class' => 'Mini Private', 'level' => 1, 'completed' => 4, 'renewal' => 'lanjut', 'is_active' => true],
            ['name' => 'Keysha Ramadhani', 'gender' => 'P', 'program' => 'mini-private', 'status' => 'diterima', 'class' => 'Mini Private', 'level' => 1, 'completed' => 1, 'renewal' => 'belum_konfirmasi', 'is_active' => true],
            ['name' => 'Fajar Ramadhan', 'gender' => 'L', 'program' => 'reguler', 'status' => 'diterima', 'class' => 'Reguler', 'level' => 1, 'completed' => 7, 'renewal' => 'lanjut', 'is_active' => true],
            ['name' => 'Aisyah Nur', 'gender' => 'P', 'program' => 'reguler', 'status' => 'diterima', 'class' => 'Reguler', 'level' => 1, 'completed' => 6, 'renewal' => 'lanjut', 'is_active' => true],
            ['name' => 'Bintang Mahesa', 'gender' => 'L', 'program' => 'reguler', 'status' => 'diterima', 'class' => 'Reguler', 'level' => 1, 'completed' => 8, 'renewal' => 'belum_konfirmasi', 'is_active' => true],
            ['name' => 'Zahra Amalia', 'gender' => 'P', 'program' => 'reguler', 'status' => 'diterima', 'class' => 'Reguler', 'level' => 1, 'completed' => 4, 'renewal' => 'lanjut', 'is_active' => true],
            ['name' => 'Rizky Ardiansyah', 'gender' => 'L', 'program' => 'reguler', 'status' => 'diterima', 'class' => 'Reguler', 'level' => 1, 'completed' => 2, 'renewal' => 'belum_konfirmasi', 'is_active' => true],
            ['name' => 'Naila Safitri', 'gender' => 'P', 'program' => 'reguler', 'status' => 'diterima', 'class' => 'Reguler', 'level' => 1, 'completed' => 8, 'renewal' => 'lanjut', 'is_active' => true],
            ['name' => 'Yoga Saputra', 'gender' => 'L', 'program' => 'reguler', 'status' => 'diterima', 'class' => 'Reguler', 'level' => 1, 'completed' => 1, 'renewal' => 'lanjut', 'is_active' => true],
            ['name' => 'Citra Lestari', 'gender' => 'P', 'program' => 'reguler', 'status' => 'menunggu_verifikasi', 'class' => null, 'completed' => 0, 'renewal' => null, 'is_active' => true],
            ['name' => 'Aldi Firmansyah', 'gender' => 'L', 'program' => 'reguler', 'status' => 'ditolak', 'class' => null, 'completed' => 0, 'renewal' => null, 'is_active' => true, 'rejection_reason' => 'Dokumen pendaftaran tidak lengkap'],
            ['name' => 'Bella Septiana', 'gender' => 'P', 'program' => 'mini-reguler', 'status' => 'diterima', 'class' => 'Mini Reguler', 'level' => 1, 'completed' => 3, 'renewal' => 'lanjut', 'is_active' => true],
            ['name' => 'Gilang Pratama', 'gender' => 'L', 'program' => 'mini-reguler', 'status' => 'diterima', 'class' => 'Mini Reguler', 'level' => 1, 'completed' => 4, 'renewal' => 'lanjut', 'is_active' => true],
            ['name' => 'Talitha Azzahra', 'gender' => 'P', 'program' => 'mini-reguler', 'status' => 'diterima', 'class' => 'Mini Reguler', 'level' => 1, 'completed' => 1, 'renewal' => 'belum_konfirmasi', 'is_active' => true],
            ['name' => 'Hafiz Ramadhan', 'gender' => 'L', 'program' => 'mini-reguler', 'status' => 'diterima', 'class' => 'Mini Reguler', 'level' => 1, 'completed' => 4, 'renewal' => 'belum_konfirmasi', 'is_active' => true],
            ['name' => 'Kirana Ayu', 'gender' => 'P', 'program' => 'mini-reguler', 'status' => 'diterima', 'class' => 'Mini Reguler', 'level' => 1, 'completed' => 2, 'renewal' => 'pindah', 'is_active' => false],
            ['name' => 'Tegar Setiawan', 'gender' => 'L', 'program' => 'mini-reguler', 'status' => 'ditolak', 'class' => null, 'completed' => 0, 'renewal' => null, 'is_active' => true, 'rejection_reason' => 'Kuota kelas penuh'],
            ['name' => 'Mutiara Hati', 'gender' => 'P', 'program' => 'kompetitif', 'status' => 'diterima', 'class' => 'Kompetitif B', 'level' => 2, 'completed' => 4, 'renewal' => 'lanjut', 'is_active' => true],
            ['name' => 'Wildan Hakim', 'gender' => 'L', 'program' => 'kompetitif', 'status' => 'diterima', 'class' => 'Kompetitif B', 'level' => 2, 'completed' => 2, 'renewal' => 'lanjut', 'is_active' => true],
            ['name' => 'Alya Ramadhani', 'gender' => 'P', 'program' => 'kompetitif', 'status' => 'diterima', 'class' => 'Kompetitif B', 'level' => 2, 'completed' => 6, 'renewal' => 'lanjut', 'is_active' => true],
            ['name' => 'Satrio Wibowo', 'gender' => 'L', 'program' => 'kompetitif', 'status' => 'diterima', 'class' => 'Kompetitif B', 'level' => 2, 'completed' => 3, 'renewal' => 'lanjut', 'is_active' => true],
            ['name' => 'Dinda Permata', 'gender' => 'P', 'program' => 'kompetitif', 'status' => 'diterima', 'class' => 'Kompetitif A', 'level' => 3, 'completed' => 5, 'renewal' => 'lanjut', 'is_active' => true],
            ['name' => 'Evan Kurniawan', 'gender' => 'L', 'program' => 'kompetitif', 'status' => 'diterima', 'class' => 'Kompetitif A', 'level' => 3, 'completed' => 2, 'renewal' => 'lanjut', 'is_active' => true],
            ['name' => 'Syifa Nabila', 'gender' => 'P', 'program' => 'kompetitif', 'status' => 'menunggu_verifikasi', 'class' => null, 'completed' => 0, 'renewal' => null, 'is_active' => true],
            ['name' => 'Luthfi Maulana', 'gender' => 'L', 'program' => 'kompetitif', 'status' => 'menunggu_verifikasi', 'class' => null, 'completed' => 0, 'renewal' => null, 'is_active' => true],
            ['name' => 'Anindya Putri', 'gender' => 'P', 'program' => 'mini-reguler', 'status' => 'ditolak', 'class' => null, 'completed' => 0, 'renewal' => null, 'is_active' => true, 'rejection_reason' => 'Usia belum memenuhi syarat'],
        ])->keyBy('name')->all();
    }
}
