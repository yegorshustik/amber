<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use Kalnoy\Nestedset\NestedSet;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('filemanager_directories', function (Blueprint $table) {
            $table->id();
            NestedSet::columns($table);
            $table->timestamps();
            $table->string('title');
            $table->integer('position')->default(0);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('filemanager_directories');
    }
};
