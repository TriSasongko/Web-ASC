<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class LandingCoach extends Model
{
    protected $fillable = [
        'name', 'position', 'description', 'photo_url', 'sort_order', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function getPhotoAttribute(): ?string
    {
        $value = $this->photo_url;

        if (! $value) {
            return null;
        }

        if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
            return $value;
        }

        return Storage::url($value);
    }
}
