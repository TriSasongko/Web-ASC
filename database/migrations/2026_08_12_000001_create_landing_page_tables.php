<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('landing_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        Schema::create('landing_coaches', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('position');
            $table->text('description');
            $table->text('photo_url')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('landing_programs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('subtitle')->nullable();
            $table->unsignedBigInteger('price')->nullable();
            $table->string('billing_unit')->default('/sesi');
            $table->text('features')->nullable();
            $table->string('badge')->nullable();
            $table->string('button_label')->nullable();
            $table->boolean('featured')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('landing_gallery', function (Blueprint $table) {
            $table->id();
            $table->text('image_url');
            $table->string('title')->nullable();
            $table->string('description')->nullable();
            $table->string('category')->nullable();
            $table->string('aspect')->default('square');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('landing_settings');
        Schema::dropIfExists('landing_coaches');
        Schema::dropIfExists('landing_programs');
        Schema::dropIfExists('landing_gallery');
    }
};
