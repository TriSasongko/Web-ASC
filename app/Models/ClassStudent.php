<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassStudent extends Model
{
    public const RENEWAL_STATUS_BELUM_KONFIRMASI = 'belum_konfirmasi';

    public const RENEWAL_STATUS_LANJUT = 'lanjut';

    public const RENEWAL_STATUS_BERHENTI = 'berhenti';

    public const RENEWAL_STATUS_PINDAH = 'pindah';

    public const RENEWAL_STATUS_PERLU_KONFIRMASI = 'perlu_konfirmasi';

    public const RENEWAL_STATUS_SELESAI = 'selesai';

    public const RENEWAL_STATUS_AKTIF = 'aktif';

    /**
     * Ambang peringatan dini dalam jumlah sesi sisa.
     * 1 = flag saat sisa sesi <= 1 (mendekati habis), 0 = flag saat tepat habis.
     */
    public const RENEWAL_FLAG_THRESHOLD = 1;

    protected $table = 'class_student';

    protected $fillable = [
        'class_id', 'student_id', 'level', 'registration_id', 'sessions_completed',
        'is_active', 'renewal_status', 'renewal_note', 'renewed_at',
        'started_at', 'ended_at', 'renewed_from_id',
    ];

    protected function casts(): array
    {
        return [
            'level' => 'integer',
            'sessions_completed' => 'integer',
            'is_active' => 'boolean',
            'renewed_at' => 'datetime',
            'started_at' => 'datetime',
            'ended_at' => 'datetime',
        ];
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }

    public function renewedFrom()
    {
        return $this->belongsTo(self::class, 'renewed_from_id');
    }

    public function renewals()
    {
        return $this->hasMany(self::class, 'renewed_from_id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function remainingSessions(): ?int
    {
        $total = $this->schoolClass?->program?->total_sessions;

        return $total === null ? null : max(0, $total - $this->sessions_completed);
    }

    public function isFinished(): bool
    {
        $total = $this->schoolClass?->program?->total_sessions;

        return $total !== null && $this->sessions_completed >= $total;
    }

    public function markForRenewalIfNeeded(): void
    {
        $program = $this->schoolClass?->program;

        if ($program?->billing_type !== 'per_paket' || $program->total_sessions === null) {
            return;
        }

        if ($this->sessions_completed < $program->total_sessions - self::RENEWAL_FLAG_THRESHOLD) {
            return;
        }

        $terminalStatuses = [
            self::RENEWAL_STATUS_PERLU_KONFIRMASI,
            self::RENEWAL_STATUS_LANJUT,
            self::RENEWAL_STATUS_BERHENTI,
            self::RENEWAL_STATUS_PINDAH,
            self::RENEWAL_STATUS_SELESAI,
        ];

        if (! in_array($this->renewal_status, $terminalStatuses, true)) {
            $this->update(['renewal_status' => self::RENEWAL_STATUS_PERLU_KONFIRMASI]);
        }
    }

    public function renewIntoNextPeriod(): self
    {
        $next = self::create([
            'class_id' => $this->class_id,
            'student_id' => $this->student_id,
            'level' => $this->level,
            'registration_id' => $this->registration_id,
            'sessions_completed' => 0,
            'is_active' => true,
            'renewal_status' => self::RENEWAL_STATUS_AKTIF,
            'started_at' => now(),
            'renewed_from_id' => $this->id,
        ]);

        $this->update([
            'is_active' => false,
            'renewal_status' => self::RENEWAL_STATUS_SELESAI,
            'ended_at' => now(),
        ]);

        return $next;
    }

    public function completeRenewalIfReady(): void
    {
        if ($this->renewal_status !== self::RENEWAL_STATUS_LANJUT || ! $this->isFinished()) {
            return;
        }

        $this->renewIntoNextPeriod();
    }
}
