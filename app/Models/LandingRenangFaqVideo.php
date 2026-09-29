<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LandingRenangFaqVideo extends Model
{
    protected $table = 'landing_renang_faq_videos';

    protected $fillable = [
        'renang_faq_id', 'title', 'youtube_url', 'sort_order',
    ];

    public function faq(): BelongsTo
    {
        return $this->belongsTo(LandingRenangFaq::class, 'renang_faq_id');
    }

    public function getEmbedUrlAttribute(): ?string
    {
        return LandingSetting::youtubeEmbedUrl($this->youtube_url);
    }
}
