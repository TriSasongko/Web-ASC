<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Development extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_id', 'student_id', 'coach_id', 'period',
        'adaptasi_air', 'mengapung', 'gerakan_kaki', 'gerakan_tangan', 'pernapasan',
        'gaya_bebas', 'gaya_dada', 'gaya_punggung', 'gaya_kupu_kupu', 'coach_note',
    ];

    // Daftar aspek penilaian, dipakai berulang di banyak view
    public static function aspects(): array
    {
        return [
            'adaptasi_air' => 'Adaptasi Air',
            'mengapung' => 'Mengapung',
            'gerakan_kaki' => 'Gerakan Kaki',
            'gerakan_tangan' => 'Gerakan Tangan',
            'pernapasan' => 'Pernapasan',
            'gaya_bebas' => 'Gaya Bebas',
            'gaya_dada' => 'Gaya Dada',
            'gaya_punggung' => 'Gaya Punggung',
            'gaya_kupu_kupu' => 'Gaya Kupu-Kupu',
        ];
    }

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function coach()
    {
        return $this->belongsTo(User::class, 'coach_id');
    }
}
