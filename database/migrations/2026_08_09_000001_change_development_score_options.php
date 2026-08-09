<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private array $oldScores = ['belum', 'cukup', 'baik', 'sangat_baik'];

    private array $newScores = ['kurang', 'cukup', 'baik', 'sangat_baik'];

    // Kolom aspek lama saat migration ini dibuat
    private array $aspects = [
        'adaptasi_air',
        'mengapung',
        'gerakan_kaki',
        'gerakan_tangan',
        'pernapasan',
        'gaya_bebas',
        'gaya_dada',
        'gaya_punggung',
        'gaya_kupu_kupu',
    ];

    private function mergedScores(): array
    {
        return array_values(array_unique([...$this->oldScores, ...$this->newScores]));
    }

    public function up(): void
    {
        $aspects = $this->aspects;

        // 1. Perluas enum sementara agar data lama tetap valid
        Schema::table('developments', function (Blueprint $table) use ($aspects) {
            foreach ($aspects as $key) {
                $table->enum($key, $this->mergedScores())
                    ->nullable(false)
                    ->default('belum')
                    ->change();
            }
        });

        // 2. Migrasi nilai lama 'belum' -> 'kurang'
        foreach ($aspects as $key) {
            DB::table('developments')->where($key, 'belum')->update([$key => 'kurang']);
        }

        // 3. Kunci ke daftar opsi poin final
        Schema::table('developments', function (Blueprint $table) use ($aspects) {
            foreach ($aspects as $key) {
                $table->enum($key, $this->newScores)
                    ->nullable(false)
                    ->default('kurang')
                    ->change();
            }
        });
    }

    public function down(): void
    {
        $aspects = $this->aspects;

        Schema::table('developments', function (Blueprint $table) use ($aspects) {
            foreach ($aspects as $key) {
                $table->enum($key, $this->mergedScores())
                    ->nullable(false)
                    ->default('kurang')
                    ->change();
            }
        });

        foreach ($aspects as $key) {
            DB::table('developments')->where($key, 'kurang')->update([$key => 'belum']);
        }

        Schema::table('developments', function (Blueprint $table) use ($aspects) {
            foreach ($aspects as $key) {
                $table->enum($key, $this->oldScores)
                    ->nullable(false)
                    ->default('belum')
                    ->change();
            }
        });
    }
};
