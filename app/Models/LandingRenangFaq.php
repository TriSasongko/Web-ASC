<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LandingRenangFaq extends Model
{
    protected $table = 'landing_renang_faqs';

    protected $fillable = [
        'question', 'answer', 'sort_order', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function videos(): HasMany
    {
        return $this->hasMany(LandingRenangFaqVideo::class, 'renang_faq_id')->orderBy('sort_order')->orderBy('id');
    }
}
