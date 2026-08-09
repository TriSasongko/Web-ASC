<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private array $styles = ['bebas', 'dada', 'punggung', 'kupu_kupu'];

    private array $aspects = [
        'gerakan_kaki',
        'gerakan_tangan',
        'gerakan_nafas',
        'koordinasi',
        'konsistensi_gerakan',
    ];

    private array $oldAspects = ['gerakan_kaki', 'gerakan_tangan', 'gerakan_nafas', 'koordinasi', 'konsistensi_gerakan'];

    private array $scores = ['kurang', 'cukup', 'baik', 'sangat_baik'];

    public function up(): void
    {
        Schema::table('developments', function (Blueprint $table) {
            foreach ($this->styles as $style) {
                foreach ($this->aspects as $aspect) {
                    $table->enum($style.'_'.$aspect, $this->scores)
                        ->nullable(false)
                        ->default('kurang');
                }
            }
        });

        Schema::table('developments', function (Blueprint $table) {
            $table->dropColumn($this->oldAspects);
        });
    }

    public function down(): void
    {
        Schema::table('developments', function (Blueprint $table) {
            foreach ($this->oldAspects as $aspect) {
                $table->enum($aspect, $this->scores)->nullable(false)->default('kurang');
            }
        });

        Schema::table('developments', function (Blueprint $table) {
            foreach ($this->styles as $style) {
                foreach ($this->aspects as $aspect) {
                    $table->dropColumn($style.'_'.$aspect);
                }
            }
        });
    }
};
