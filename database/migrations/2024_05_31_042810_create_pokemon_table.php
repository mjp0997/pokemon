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
        Schema::create('pokemons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('generation_id')->index()->constrained('generations')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('habitat_id')->index()->constrained('habitats')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('color_id')->index()->constrained('colors')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('name')->unique();
            $table->integer('api_id');
            $table->string('sprite')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pokemons');
    }
};
