<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BestTime extends Model
{
    use HasFactory;

    public const STYLE_BEBAS = 'bebas';

    public const STYLE_DADA = 'dada';

    public const STYLE_PUNGGUNG = 'punggung';

    public const STYLE_KUPU_KUPU = 'kupu_kupu';

    protected $fillable = [
        'student_id', 'class_id', 'recorded_by', 'style', 'distance', 'time_ms', 'recorded_at',
    ];

    protected function casts(): array
    {
        return [
            'recorded_at' => 'date',
        ];
    }

    // Daftar gaya renang (sama dengan penilaian perkembangan)
    public static function styles(): array
    {
        return Development::styles();
    }

    // Jarak yang dilombakan per gaya (Gaya Bebas termasuk 400 m)
    public static function distancesByStyle(): array
    {
        return [
            self::STYLE_KUPU_KUPU => [200, 100, 50, 25],
            self::STYLE_DADA => [200, 100, 50, 25],
            self::STYLE_PUNGGUNG => [200, 100, 50, 25],
            self::STYLE_BEBAS => [400, 200, 100, 50, 25],
        ];
    }

    // Semua jarak (urut menurun) untuk header tabel
    public static function allDistances(): array
    {
        return [400, 200, 100, 50, 25];
    }

    public static function styleLabel(string $style): string
    {
        return self::styles()[$style] ?? $style;
    }

    public static function distanceLabel(int $distance): string
    {
        return $distance.' m';
    }

    // Format total milidetik menjadi "Menit:Detik:MiliDetik", misal 85037 -> "01:25:37"
    public static function formatTime(int $ms): string
    {
        $minutes = intdiv($ms, 60000);
        $seconds = intdiv($ms % 60000, 1000);
        $millis = $ms % 1000;

        return sprintf('%02d:%02d:%02d', $minutes, $seconds, $millis);
    }

    // Parse "Menit:Detik:MiliDetik" (contoh 01:25:37) menjadi total milidetik; null jika tidak valid
    public static function parseTime(?string $value): ?int
    {
        if ($value === null || trim($value) === '') {
            return null;
        }

        if (! preg_match('/^(\d{1,3}):(\d{1,2}):(\d{1,3})$/', trim($value), $m)) {
            return null;
        }

        $minutes = (int) $m[1];
        $seconds = (int) $m[2];
        $millis = (int) $m[3];

        if ($minutes > 599 || $seconds > 59 || $millis > 999) {
            return null;
        }

        return $minutes * 60000 + $seconds * 1000 + $millis;
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function recorder()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}
