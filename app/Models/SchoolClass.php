<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolClass extends Model
{
    use HasFactory;

    protected $table = 'classes'; // wajib, karena nama model ≠ nama tabel

    public const LEVEL_BEGINNER = 1;

    public const LEVEL_ADVANCE = 2;

    public const LEVEL_ELITE = 3;

    protected $fillable = [
        'program_id', 'coach_id', 'name', 'level', 'capacity', 'is_active',
    ];

    public static function levelOptions(): array
    {
        return [
            self::LEVEL_BEGINNER => 'Beginner',
            self::LEVEL_ADVANCE => 'Advance',
            self::LEVEL_ELITE => 'Elite',
        ];
    }

    public function getLevelLabelAttribute(): ?string
    {
        return self::levelOptions()[$this->level] ?? null;
    }

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function coach()
    {
        return $this->belongsTo(User::class, 'coach_id');
    }

    public function schedules()
    {
        return $this->hasMany(ClassSchedule::class, 'class_id');
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'class_student', 'class_id', 'student_id')
            ->withPivot(['registration_id', 'sessions_completed', 'is_active'])
            ->withTimestamps();
    }
}
