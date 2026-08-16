<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class LandingSetting extends Model
{
    protected $fillable = ['key', 'value'];

    public static function allValues(): array
    {
        return static::pluck('value', 'key')->all();
    }

    public static function get(string $key, ?string $default = null): ?string
    {
        return static::firstWhere('key', $key)?->value ?? $default;
    }

    public static function set(string $key, ?string $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
    }

    // Ubah nilai (path upload atau URL eksternal) menjadi URL yang bisa dirender
    public static function resolveUrl(?string $value): ?string
    {
        if (! $value) {
            return null;
        }

        if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
            return $value;
        }

        return Storage::url($value);
    }

    // Nilai pengaturan dengan gambar yang sudah diubah menjadi URL render
    public static function resolvedValues(): array
    {
        $values = static::allValues();

        foreach (['hero_image', 'hero_side_image', 'tentang_image'] as $key) {
            if (! empty($values[$key])) {
                $values[$key] = static::resolveUrl($values[$key]);
            }
        }

        return $values;
    }
}
