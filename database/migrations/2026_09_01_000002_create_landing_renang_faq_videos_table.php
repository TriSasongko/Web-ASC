<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('landing_renang_faq_videos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('renang_faq_id')->constrained('landing_renang_faqs')->cascadeOnDelete();
            $table->string('title', 255)->nullable();
            $table->string('youtube_url', 2000)->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        // Pindahkan video lama (kolom tunggal) ke tabel baru tanpa kehilangan data.
        $legacy = DB::table('landing_renang_faqs')->whereNotNull('youtube_url')->get();
        foreach ($legacy as $faq) {
            DB::table('landing_renang_faq_videos')->insert([
                'renang_faq_id' => $faq->id,
                'title' => null,
                'youtube_url' => $faq->youtube_url,
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        Schema::table('landing_renang_faqs', function (Blueprint $table) {
            $table->dropColumn('youtube_url');
        });
    }

    public function down(): void
    {
        Schema::table('landing_renang_faqs', function (Blueprint $table) {
            $table->string('youtube_url', 2000)->nullable()->after('answer');
        });

        $videos = DB::table('landing_renang_faq_videos')->get();
        foreach ($videos as $video) {
            DB::table('landing_renang_faqs')
                ->where('id', $video->renang_faq_id)
                ->whereNull('youtube_url')
                ->update(['youtube_url' => $video->youtube_url]);
        }

        Schema::dropIfExists('landing_renang_faq_videos');
    }
};
