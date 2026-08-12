<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
