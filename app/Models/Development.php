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
        'daya_tahan', 'recovery', 'water_survive', 'coach_note',
        'bebas_gerakan_kaki', 'bebas_gerakan_tangan', 'bebas_gerakan_nafas', 'bebas_koordinasi', 'bebas_konsistensi_gerakan',
        'dada_gerakan_kaki', 'dada_gerakan_tangan', 'dada_gerakan_nafas', 'dada_koordinasi', 'dada_konsistensi_gerakan',
        'punggung_gerakan_kaki', 'punggung_gerakan_tangan', 'punggung_gerakan_nafas', 'punggung_koordinasi', 'punggung_konsistensi_gerakan',
        'kupu_kupu_gerakan_kaki', 'kupu_kupu_gerakan_tangan', 'kupu_kupu_gerakan_nafas', 'kupu_kupu_koordinasi', 'kupu_kupu_konsistensi_gerakan',
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

    // Daftar gaya renang yang dinilai
    public static function styles(): array
    {
        return [
            'bebas' => 'Gaya Bebas',
            'dada' => 'Gaya Dada',
            'punggung' => 'Gaya Punggung',
            'kupu_kupu' => 'Gaya Kupu-Kupu',
        ];
    }

    // Daftar aspek penilaian khusus, diterapkan pada setiap gaya renang
    public static function khususAspects(): array
    {
        return [
            'gerakan_kaki' => 'Gerakan Kaki',
            'gerakan_tangan' => 'Gerakan Tangan',
            'gerakan_nafas' => 'Gerakan Napas',
            'koordinasi' => 'Koordinasi Gerakan',
            'konsistensi_gerakan' => 'Konsistensi Gerakan',
        ];
    }

    // Kunci kolom untuk aspek khusus: "{gaya}_{aspek}"
    public static function styleAspectKey(string $style, string $aspect): string
    {
        return $style.'_'.$aspect;
    }

    // Gabungan semua aspek, dipakai untuk validasi saat menyimpan
    public static function aspects(): array
    {
        $special = [];
        foreach (self::styles() as $style => $styleLabel) {
            foreach (self::khususAspects() as $aspect => $label) {
                $special[self::styleAspectKey($style, $aspect)] = $styleLabel.' — '.$label;
            }
        }

        return [...self::umumAspects(), ...$special];
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
