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
        Schema::create('articles_rubrics', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('image')->nullable();
            $table->string('title')->nullable();
            $table->string('slug')->nullable();
            $table->boolean('is_published')->default(true);
            $table->longText('seo')->nullable();
            $table->longText('content')->nullable();
            $table->integer('position')->default(0);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('articles_rubrics');
    }
};
