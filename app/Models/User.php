<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'phone', 'address', 'is_active', 'photo', 'can_assess_developments',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'can_assess_developments' => 'boolean',
        ];
    }

    // Helper cek role
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isPelatih(): bool
    {
        return $this->role === 'pelatih';
    }

    public function isOrangTua(): bool
    {
        return $this->role === 'orang_tua';
    }

    // Helper izin mengisi penilaian perkembangan siswa
    public function canAssessDevelopments(): bool
    {
        return (bool) $this->can_assess_developments;
    }

    // Ambil nomor telepon/WA pengguna dengan role admin (sumber kontak website)
    public static function adminPhone(): string
    {
        $admin = static::where('role', 'admin')
            ->whereNotNull('phone')
            ->where('phone', '!=', '')
            ->orderBy('id')
            ->first();

        return $admin ? self::normalizePhoneDigits($admin->phone) : '6281234567890';
    }

    // Format nomor tampilan (mis. +62 812-3456-7890)
    public static function adminWaDisplay(): string
    {
        $digits = self::adminPhone();

        if (Str::startsWith($digits, '62')) {
            $rest = substr($digits, 2);
            $len = strlen($rest);

            if ($len === 11) {
                return '+62 '.substr($rest, 0, 3).'-'.substr($rest, 3, 4).'-'.substr($rest, 7, 4);
            }

            if ($len === 10) {
                return '+62 '.substr($rest, 0, 3).'-'.substr($rest, 3, 3).'-'.substr($rest, 6, 4);
            }

            if ($len === 9) {
                return '+62 '.substr($rest, 0, 3).'-'.substr($rest, 3, 3).'-'.substr($rest, 6, 3);
            }
        }

        return '+'.$digits;
    }

    // Tautan chat WhatsApp admin
    public static function adminWaLink(): string
    {
        return 'https://wa.me/'.self::adminPhone();
    }

    // Tautan panggilan telepon admin
    public static function adminTelLink(): string
    {
        return 'tel:+'.self::adminPhone();
    }

    // Alamat admin (sumber alamat website); fallback ke alamat default jika belum diisi
    public static function adminAddress(): string
    {
        $address = static::where('role', 'admin')
            ->whereNotNull('address')
            ->where('address', '!=', '')
            ->orderBy('id')
            ->value('address');

        return $address ?: 'Jl. Prof. Dr. Ir. Sumantri Brojonegoro No.1, Gedong Meneng, Kec. Rajabasa, Kota Bandar Lampung, Lampung 35145';
    }

    // Normalisasi nomor: ambil angka, ubah awalan 0 (lokal) menjadi 62 (internasional)
    private static function normalizePhoneDigits(string $phone): string
    {
        $digits = preg_replace('/\D+/', '', $phone);

        if (Str::startsWith($digits, '00')) {
            $digits = substr($digits, 2);
        }

        if (Str::startsWith($digits, '0')) {
            $digits = '62'.substr($digits, 1);
        }

        return $digits;
    }

    // Relasi jika role = orang_tua
    public function students()
    {
        return $this->hasMany(Student::class, 'parent_id');
    }

    // Jadwal latihan yang diampu pelatih ini (bisa lebih dari satu pelatih per sesi)
    public function coachedSchedules()
    {
        return $this->belongsToMany(ClassSchedule::class, 'class_schedule_user', 'user_id', 'class_schedule_id')
            ->withTimestamps();
    }
}
