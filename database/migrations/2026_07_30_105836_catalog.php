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
        Schema::create('catalog', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('type')->default(\App\Enums\Catalog\ItemType::SCHOOL->value);
            $table->text('title')->nullable();
            $table->boolean('is_published')->default(true);
            $table->boolean('is_visible')->default(true);
            $table->string('slug')->nullable();
            $table->text('country')->nullable();
            $table->text('city')->nullable();
            $table->text('short_details')->nullable();
            $table->text('details')->nullable();
            $table->text('age_range')->nullable();
            $table->text('gender')->nullable();
            $table->text('boarding')->nullable();
            $table->text('curriculum')->nullable();
            $table->text('size')->nullable();
            $table->text('campus_style')->nullable();
            $table->text('programs')->nullable();
            $table->text('degrees')->nullable();
            $table->text('acceptance')->nullable();
            $table->text('established')->nullable();
            $table->string('image')->nullable();
            $table->text('pre_heading')->nullable();
            $table->text('heading')->nullable();
            $table->longText('content')->nullable();
            $table->longText('faq')->nullable();
            $table->longText('seo')->nullable();

            $table->integer('position')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
