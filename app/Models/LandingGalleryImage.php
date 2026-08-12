<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LandingGalleryImage extends Model
{
    protected $table = 'landing_gallery';

    protected $fillable = [
        'image_url', 'title', 'description', 'category', 'aspect', 'sort_order', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function aspectClass(): string
    {
        return match ($this->aspect) {
            '3/4' => 'aspect-[3/4]',
            '4/3' => 'aspect-[4/3]',
            '4/5' => 'aspect-[4/5]',
            'video' => 'aspect-video',
            default => 'aspect-square',
        };
    }
}
