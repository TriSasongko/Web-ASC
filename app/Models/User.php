<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

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

    // Relasi jika role = orang_tua
    public function students()
    {
        return $this->hasMany(Student::class, 'parent_id');
    }
}
