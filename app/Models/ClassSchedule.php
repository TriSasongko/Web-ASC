<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassSchedule extends Model
{
    use HasFactory;

    public const DAYS = ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu'];

    protected $fillable = [
        'class_id', 'day', 'start_time', 'end_time', 'location', 'session_number',
    ];

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'class_schedule_student', 'class_schedule_id', 'student_id')
            ->withTimestamps();
    }

    public function coaches()
    {
        return $this->belongsToMany(User::class, 'class_schedule_user', 'class_schedule_id', 'user_id')
            ->withTimestamps();
    }
}
