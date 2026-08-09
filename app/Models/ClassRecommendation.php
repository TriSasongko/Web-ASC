<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassRecommendation extends Model
{
    protected $fillable = [
        'student_id', 'from_user_id', 'current_class_id',
        'recommended_class_id', 'recommended_level', 'note', 'status',
    ];

    protected function casts(): array
    {
        return [
            'recommended_level' => 'integer',
        ];
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function from()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    public function currentClass()
    {
        return $this->belongsTo(SchoolClass::class, 'current_class_id');
    }

    public function recommendedClass()
    {
        return $this->belongsTo(SchoolClass::class, 'recommended_class_id');
    }
}
