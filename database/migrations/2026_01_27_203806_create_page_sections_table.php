<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('page_sections', function (Blueprint $table) {
            $table->id();
            $table->string('page')->default('home'); // home, about, etc.
            $table->string('section_key'); // hero_title, hero_subtitle, hero_image, etc.
            $table->string('section_type')->default('text'); // text, image, html, json
            $table->text('content')->nullable();
            $table->json('metadata')->nullable(); // Additional data
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            
            $table->unique(['page', 'section_key']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_sections');
    }
};
