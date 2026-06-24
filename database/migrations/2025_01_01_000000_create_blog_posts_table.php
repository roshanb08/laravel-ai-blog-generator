<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blog_posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('meta_description')->nullable();
            $table->longText('content');
            $table->string('keywords')->nullable();    // comma-separated, direct from API
            $table->string('og_title')->nullable();
            $table->text('og_description')->nullable();
            $table->string('og_image')->nullable();
            $table->json('sources')->nullable();       // array of source URLs
            $table->unsignedTinyInteger('articles_used')->default(0);
            $table->string('category')->nullable();
            $table->string('country', 10)->nullable();
            $table->unsignedSmallInteger('reading_time')->default(5);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            $table->index('published_at');
            $table->index('category');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blog_posts');
    }
};
