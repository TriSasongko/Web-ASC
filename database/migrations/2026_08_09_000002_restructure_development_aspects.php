<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private array $scores = ['kurang', 'cukup', 'baik', 'sangat_baik'];

    private array $newUmum = [
        'adaptasi_lingkungan_baru',
        'komunikasi',
        'menerima_instruksi',
        'disiplin',
        'percaya_diri',
        'daya_tahan',
        'recovery',
        'water_survive',
    ];

    private array $newKhusus = ['gerakan_nafas', 'koordinasi', 'konsistensi_gerakan'];

    private array $oldColumns = [
        'adaptasi_air',
        'mengapung',
        'pernapasan',
        'gaya_bebas',
        'gaya_dada',
        'gaya_punggung',
        'gaya_kupu_kupu',
    ];

    public function up(): void
    {
        // 1. Tambah kolom penilaian baru (umum + aspek khusus)
        Schema::table('developments', function (Blueprint $table) {
            foreach ([...$this->newUmum, ...$this->newKhusus] as $key) {
                $table->enum($key, $this->scores)->nullable(false)->default('kurang');
            }
        });

        // 2. Hapus kolom penilaian lama
        Schema::table('developments', function (Blueprint $table) {
            $table->dropColumn($this->oldColumns);
        });
    }

    public function down(): void
    {
        // 1. Kembalikan kolom penilaian lama
        Schema::table('developments', function (Blueprint $table) {
            foreach ($this->oldColumns as $key) {
                $table->enum($key, $this->scores)->nullable(false)->default('kurang');
            }
        });

        // 2. Hapus kolom penilaian baru
        Schema::table('developments', function (Blueprint $table) {
            $table->dropColumn([...$this->newUmum, ...$this->newKhusus]);
        });
    }
};
