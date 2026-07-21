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
        Schema::create('inbox_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form_id')->constrained('inbox_forms')->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamps();
            $table->string('hash')->nullable()->index();
            $table->longText('options')->nullable();
        });

        Schema::create('inbox_application_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form_id')->constrained('inbox_forms')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('application_id')->constrained('inbox_applications')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('field_id')->constrained('inbox_form_fields')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('content')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inbox_application_fields');
        Schema::dropIfExists('inbox_applications');
    }
};
