<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassStudent extends Model
{
    protected $table = 'class_student';

    protected $fillable = [
        'class_id', 'student_id', 'registration_id', 'sessions_completed',
        'is_active', 'renewal_status', 'renewal_note', 'renewed_at',
    ];

    protected function casts(): array
    {
        return [
            'sessions_completed' => 'integer',
            'is_active' => 'boolean',
            'renewed_at' => 'datetime',
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
