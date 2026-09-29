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

    // Ubah URL YouTube biasa menjadi URL embed (untuk iframe)
    // Mendukung: youtube.com/watch?v=, youtu.be/, youtube.com/shorts/, dan embed/ yang sudah jadi
    public static function youtubeEmbedUrl(?string $url): ?string
    {
        if (! $url) {
            return null;
        }

        $url = trim($url);

        if (! preg_match('#^https?://(www\.)?(youtube\.com|youtu\.be)/#i', $url)) {
            return null;
        }

        $host = parse_url($url, PHP_URL_HOST);
        $path = parse_url($url, PHP_URL_PATH);

        // Sudah berupa embed
        if (str_contains($path, '/embed/')) {
            return $url;
        }

        $videoId = null;

        if (str_ends_with(strtolower((string) $host), 'youtu.be')) {
            $videoId = trim((string) $path, '/');
        } elseif (preg_match('~^/(shorts|live|embed)/([^/?#]+)~i', (string) $path, $m)) {
            $videoId = $m[2];
        } else {
            parse_str((string) parse_url($url, PHP_URL_QUERY), $query);
            $videoId = $query['v'] ?? null;
        }

        if ($videoId && preg_match('/^[\w-]{11}$/', $videoId)) {
            return 'https://www.youtube.com/embed/'.$videoId;
        }

        return null;
    }

    // Parse teks biasa Syarat & Ketentuan menjadi HTML
    // Format: baris judul bagian diawali huruf (A. B. C.), baris list diawali angka (1. 2. 3.)
    public static function parseSyaratKetentuan(?string $text): string
    {
        if (! $text) {
            return '';
        }

        $lines = preg_split('/\r?\n/', $text);
        $html = '';
        $inList = false;

        foreach ($lines as $line) {
            $trimmed = trim($line);

            if ($trimmed === '') {
                if ($inList) {
                    $html .= '</ol></div>';
                    $inList = false;
                }

                continue;
            }

            // Baris judul bagian: A. ... , B. ... , dst
            if (preg_match('/^[A-Z]\.\s/', $trimmed)) {
                if ($inList) {
                    $html .= '</ol></div>';
                    $inList = false;
                }
                $html .= '<div class="space-y-3"><h4 class="font-headline text-body-md font-semibold text-on-surface">'.$trimmed.'</h4>';

                continue;
            }

            // Baris list: 1. ... , 2. ... , dst
            if (preg_match('/^\d+\.\s/', $trimmed)) {
                if (! $inList) {
                    $html .= '<ol class="list-decimal list-inside space-y-1">';
                    $inList = true;
                }
                $item = preg_replace('/^\d+\.\s/', '', $trimmed);
                $html .= '<li>'.$item.'</li>';

                continue;
            }

            // Baris biasa lainnya
            if (! $inList) {
                $html .= '<p class="text-body-sm">'.$trimmed.'</p>';
            } else {
                $html .= '<li>'.$trimmed.'</li>';
            }
        }

        if ($inList) {
            $html .= '</ol></div>';
        }

        return $html;
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
