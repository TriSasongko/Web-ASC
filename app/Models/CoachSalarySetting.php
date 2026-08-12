<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoachSalarySetting extends Model
{
    protected $fillable = [
        'user_id', 'session_limit',
    ];

    protected $casts = [
        'session_limit' => 'integer',
    ];

    public function coach()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
