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
        Schema::create('filemanager_files', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->default(1);
            $table->timestamps();
            $table->string('hash')->nullable();
            $table->string('name')->nullable();
            $table->string('file_name')->nullable();
            $table->string('mime')->nullable();
            $table->integer('size')->nullable();
            $table->string('path')->nullable();

            $table->index(['parent_id', 'hash']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('filemanager_files');
    }
};
