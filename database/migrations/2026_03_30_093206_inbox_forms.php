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
        Schema::create('inbox_forms', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('title')->nullable();
            $table->string('slug')->nullable();
            $table->boolean('is_published')->default(true);
            $table->text('recipients')->nullable();
            $table->longText('options')->nullable();
            $table->integer('position')->default(0);
        });

        Schema::create('inbox_form_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form_id')->constrained('inbox_forms')->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamps();
            $table->string('type')->default('text');
            $table->string('title')->nullable();
            $table->string('placeholder')->nullable();
            $table->boolean('is_published')->default(true);
            $table->boolean('is_required')->default(false);
            $table->boolean('is_fullsize')->default(true);
            $table->boolean('in_table')->default(false);
            $table->integer('position')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inbox_form_fields');
        Schema::dropIfExists('inbox_forms');
    }
};
