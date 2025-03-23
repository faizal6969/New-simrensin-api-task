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
        Schema::create('recipe_trackers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->text('ingredients')->nullable();
            $table->integer('prep_time')->nullable();
            $table->integer('cook_time')->nullable();
            $table->enum('difficulty', ['easy', 'medium', 'hard']);
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipe_trackers');
    }
};
