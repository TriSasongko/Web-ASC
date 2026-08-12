<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalarySetting extends Model
{
    protected $fillable = [
        'rate_reguler_satu', 'rate_reguler_dua_plus',
        'rate_paralel_dua', 'rate_paralel_banyak',
    ];

    public static function current(): self
    {
        return static::first() ?? static::create()->refresh();
    }
}
