<?php

namespace App\Console\Commands;

use App\Models\ClassStudent;
use Illuminate\Console\Command;

class CheckRenewals extends Command
{
    protected $signature = 'renewal:check';

    protected $description = 'Menandai paket yang mendekati/habis sebagai perlu_konfirmasi.';

    public function handle(): int
    {
        $enrollments = ClassStudent::with('schoolClass.program')
            ->where('is_active', true)
            ->get();

        $flagged = 0;

        foreach ($enrollments as $enrollment) {
            $before = $enrollment->renewal_status;
            $enrollment->markForRenewalIfNeeded();

            if ($enrollment->renewal_status !== $before) {
                $flagged++;
            }
        }

        $this->info("{$flagged} paket ditandai perlu konfirmasi.");

        return self::SUCCESS;
    }
}
