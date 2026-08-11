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
}
