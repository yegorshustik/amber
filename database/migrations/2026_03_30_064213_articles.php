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
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->dateTime('published_at')->nullable();
            $table->string('image')->nullable();
            $table->string('title')->nullable();
            $table->string('slug')->nullable();
            $table->boolean('is_published')->default(true);
            $table->longText('seo')->nullable();
            $table->text('announcement')->nullable();
            $table->longText('content')->nullable();
            $table->integer('position')->default(0);
        });

        Schema::create('articles_tags', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('slug')->nullable();
            $table->string('title')->nullable();
        });

        Schema::create('article_to_rubrics', function (Blueprint $table) {
            $table->foreignId('article_id')->constrained('articles')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('rubric_id')->constrained('articles_rubrics')->cascadeOnDelete()->cascadeOnUpdate();
        });

        Schema::create('article_to_tags', function (Blueprint $table) {
            $table->foreignId('article_id')->constrained('articles')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('tag_id')->constrained('articles_tags')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('article_to_tags');
        Schema::dropIfExists('article_to_rubrics');
        Schema::dropIfExists('articles_tags');
        Schema::dropIfExists('articles');
    }
};
