<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalaryPayment extends Model
{
    protected $fillable = [
        'user_id', 'amount', 'session_count', 'paid_at', 'note',
    ];

    protected $casts = [
        'amount' => 'integer',
        'session_count' => 'integer',
        'paid_at' => 'datetime',
    ];

    public function coach()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
