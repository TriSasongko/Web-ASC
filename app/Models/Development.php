<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Development extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_id', 'student_id', 'coach_id', 'period',
        'adaptasi_lingkungan_baru', 'komunikasi', 'menerima_instruksi', 'disiplin', 'percaya_diri',
        'daya_tahan', 'recovery', 'water_survive', 'gerakan_kaki', 'gerakan_tangan',
        'gerakan_nafas', 'koordinasi', 'konsistensi_gerakan', 'coach_note',
    ];

    // Daftar aspek penilaian umum, dipakai berulang di banyak view
    public static function umumAspects(): array
    {
        return [
            'adaptasi_lingkungan_baru' => 'Mampu Beradaptasi dengan Lingkungan Baru',
            'komunikasi' => 'Mampu Berkomunikasi dengan Baik',
            'menerima_instruksi' => 'Mampu Menerima Instruksi dari Pelatih',
            'disiplin' => 'Disiplin',
            'percaya_diri' => 'Percaya Diri',
            'daya_tahan' => 'Daya Tahan',
            'recovery' => 'Kemampuan Recovery/Istirahat saat Latihan',
            'water_survive' => 'Water Survive',
        ];
    }

    // Daftar aspek penilaian khusus
    public static function khususAspects(): array
    {
        return [
            'gerakan_kaki' => 'Gerakan Kaki',
            'gerakan_tangan' => 'Gerakan Tangan',
            'gerakan_nafas' => 'Gerakan Nafas',
            'koordinasi' => 'Koordinasi',
            'konsistensi_gerakan' => 'Konsistensi Gerakan',
        ];
    }

    // Gabungan semua aspek, dipakai untuk validasi saat menyimpan
    public static function aspects(): array
    {
        return [...self::umumAspects(), ...self::khususAspects()];
    }

    // Daftar opsi penilaian berbasis poin
    public static function scores(): array
    {
        return [
            'kurang' => 'Kurang',
            'cukup' => 'Cukup',
            'baik' => 'Baik',
            'sangat_baik' => 'Sangat Baik',
        ];
    }

    // Keterangan nilai, misal "Kurang"
    public static function scoreLabel(?string $value): string
    {
        return $value !== null && array_key_exists($value, self::scores()) ? self::scores()[$value] : ($value ?? '-');
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
