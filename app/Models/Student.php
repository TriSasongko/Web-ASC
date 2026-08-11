<?php

namespace App\Models;

use App\Models\Scopes\ActiveParentScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id', 'full_name', 'nickname', 'birth_place', 'birth_date',
        'gender', 'weight', 'height', 'address',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope(new ActiveParentScope);
    }

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
        ];
    }

    public function parent()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }

    public function classes()
    {
        return $this->belongsToMany(SchoolClass::class, 'class_student', 'student_id', 'class_id')
            ->withPivot(['id', 'level', 'registration_id', 'sessions_completed', 'is_active', 'renewal_status', 'renewal_note', 'renewed_at', 'started_at', 'ended_at', 'renewed_from_id'])
            ->withTimestamps();
    }

    public function enrollments()
    {
        return $this->hasMany(ClassStudent::class, 'student_id');
    }

    public function schedules()
    {
        return $this->belongsToMany(ClassSchedule::class, 'class_schedule_student', 'student_id', 'class_schedule_id')
            ->withTimestamps();
    }

    public function developments()
    {
        return $this->hasMany(Development::class);
    }

    public function recommendations()
    {
        return $this->hasMany(ClassRecommendation::class);
    }

    public function scopeActiveProgram($query)
    {
        return $query->whereHas('enrollments', fn ($q) => $q->where('is_active', true));
    }
}
