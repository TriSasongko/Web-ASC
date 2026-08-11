<?php

namespace App\Console\Commands;

use App\Models\ClassStudent;
use Illuminate\Console\Command;

class CheckRenewals extends Command
{
    /**
     * Ambang peringatan dini (dalam jumlah sesi sisa).
     * 1 = flag saat sisa sesi <= 1 (mendekati habis), 0 = flag saat tepat habis.
     */
    private const REMAINING_SESSIONS_THRESHOLD = 1;

    protected $signature = 'renewal:check';

    protected $description = 'Menandai paket yang mendekati/habis sebagai perlu_konfirmasi.';

    public function handle(): int
    {
        $terminalStatuses = [
            ClassStudent::RENEWAL_STATUS_PERLU_KONFIRMASI,
            ClassStudent::RENEWAL_STATUS_BERHENTI,
            ClassStudent::RENEWAL_STATUS_PINDAH,
            ClassStudent::RENEWAL_STATUS_SELESAI,
        ];

        $enrollments = ClassStudent::with('schoolClass.program')
            ->where('is_active', true)
            ->whereNotIn('renewal_status', $terminalStatuses)
            ->get();

        $flagged = 0;

        foreach ($enrollments as $enrollment) {
            $program = $enrollment->schoolClass?->program;

            if ($program?->billing_type !== 'per_paket' || $program->total_sessions === null) {
                continue;
            }

            $threshold = $program->total_sessions - self::REMAINING_SESSIONS_THRESHOLD;

            if ($enrollment->sessions_completed >= $threshold) {
                $enrollment->update([
                    'renewal_status' => ClassStudent::RENEWAL_STATUS_PERLU_KONFIRMASI,
                ]);
                $flagged++;
            }
        }

        $this->info("{$flagged} paket ditandai perlu konfirmasi.");

        return self::SUCCESS;
    }
}
