<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id', 'full_name', 'nickname', 'birth_place', 'birth_date',
        'gender', 'weight', 'height', 'address',
    ];

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
                     ->withPivot(['registration_id', 'sessions_completed', 'is_active'])
                     ->withTimestamps();
    }
}
