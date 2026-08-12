<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LandingProgram extends Model
{
    protected $fillable = [
        'name', 'subtitle', 'price', 'billing_unit', 'features',
        'badge', 'button_label', 'featured', 'sort_order', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'integer',
            'featured' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function featureList(): array
    {
        return collect(preg_split('/\r\n|\r|\n/', (string) $this->features))
            ->map(fn ($line) => trim($line))
            ->filter()
            ->values()
            ->all();
    }
}
