<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\CoachSalarySetting;
use App\Models\SalaryPayment;
use App\Models\SalarySetting;
use App\Models\User;
use Illuminate\Support\Collection;

class SalaryService
{
    /**
     * Hitung semua unit honor (satu unit = satu sesi latihan per kelas per tanggal)
     * dari data absensi pelatih pada program non-kompetitif, dikelompokkan per pelatih.
     *
     * @return Collection<int, array<string, mixed>> keyed by user_id
     */
    public function buildAllUnits(): Collection
    {
        $settings = SalarySetting::current();

        $attendances = Attendance::query()
            ->with(['schoolClass.program'])
            ->whereHas('schoolClass.program', fn ($q) => $q->where('is_kompetitif', false))
            ->orderBy('attendance_date')
            ->orderBy('id')
            ->get(['id', 'class_id', 'student_id', 'recorded_by', 'attendance_date', 'session_number']);

        return $attendances
            ->groupBy('recorded_by')
            ->map(fn ($byCoach) => $this->buildUnitsForCoach($byCoach, $settings));
    }

    /**
     * Unit honor milik seorang pelatih.
     *
     * @return Collection<int, array<string, mixed>>
     */
    public function sessionsForCoach(User $coach): Collection
    {
        return $this->buildAllUnits()->get($coach->id, collect());
    }

    /**
     * Unit honor yang belum masuk batch pembayaran pelatih.
     *
     * @return Collection<int, array<string, mixed>>
     */
    public function unpaidSessions(User $coach): Collection
    {
        $sessions = $this->sessionsForCoach($coach);
        $covered = (int) SalaryPayment::where('user_id', $coach->id)->sum('session_count');

        return $sessions->slice($covered)->values();
    }

    /**
     * Rekap unit honor belum dibayar untuk semua pelatih, sekali query.
     *
     * @return Collection<int, array{user_id: int, sessions: Collection, total: int}>
     */
    public function unpaidForAll(): Collection
    {
        $all = $this->buildAllUnits();

        $coveredByUser = SalaryPayment::query()
            ->selectRaw('user_id, SUM(session_count) as covered')
            ->groupBy('user_id')
            ->pluck('covered', 'user_id');

        return $all->map(function ($sessions, $userId) use ($coveredByUser) {
            $unpaid = $sessions->slice((int) ($coveredByUser[$userId] ?? 0))->values();

            return [
                'user_id' => (int) $userId,
                'sessions' => $unpaid,
                'total' => (int) $unpaid->sum('nominal'),
            ];
        });
    }

    /**
     * Tandai seluruh honor belum dibayar pelatih sebagai batch pembayaran baru.
     */
    public function markPaid(User $coach, ?string $note = null): bool
    {
        $unpaid = $this->unpaidSessions($coach);

        if ($unpaid->isEmpty()) {
            return false;
        }

        SalaryPayment::create([
            'user_id' => $coach->id,
            'amount' => (int) $unpaid->sum('nominal'),
            'session_count' => $unpaid->count(),
            'paid_at' => now(),
            'note' => $note ?: null,
        ]);

        return true;
    }

    /**
     * Batas sesi per batch honor pelatih (default 8).
     */
    public function sessionLimitForCoach(User $coach): int
    {
        return $coach->salarySetting?->session_limit
            ?? CoachSalarySetting::where('user_id', $coach->id)->value('session_limit')
            ?? 8;
    }

    /**
     * Kelompokkan absensi satu pelatih menjadi unit honor per (kelas, tanggal).
     */
    private function buildUnitsForCoach(Collection $attendances, SalarySetting $settings): Collection
    {
        return $attendances
            ->groupBy(fn (Attendance $attendance) => $attendance->class_id.'|'.$attendance->attendance_date->format('Y-m-d'))
            ->map(function ($group) use ($settings) {
                $first = $group->first();
                $session1 = $group->where('session_number', 1)->count();
                $session2 = $group->where('session_number', 2)->count();
                $bothSessions = $session1 > 0 && $session2 > 0;

                if ($bothSessions) {
                    $total = $session1 + $session2;
                    $nominal = $total >= 3
                        ? $settings->rate_paralel_banyak
                        : $settings->rate_paralel_dua;
                } else {
                    $count = max($session1, $session2);
                    $nominal = $count >= 2
                        ? $settings->rate_reguler_dua_plus
                        : $settings->rate_reguler_satu;
                }

                return [
                    'attendance_date' => $first->attendance_date,
                    'first_attendance_id' => $group->min('id'),
                    'class_id' => $first->class_id,
                    'class_name' => $first->schoolClass?->name,
                    'program_name' => $first->schoolClass?->program?->name,
                    'session1_count' => $session1,
                    'session2_count' => $session2,
                    'child_count' => $session1 + $session2,
                    'paralel' => $bothSessions,
                    'nominal' => (int) $nominal,
                ];
            })
            ->sortBy([
                ['attendance_date', 'asc'],
                ['first_attendance_id', 'asc'],
            ])
            ->values();
    }
}
