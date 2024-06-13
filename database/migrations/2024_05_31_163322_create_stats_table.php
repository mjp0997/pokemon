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
        Schema::create('stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pokemon_id')->index()->constrained('pokemons')->cascadeOnDelete()->cascadeOnUpdate();
            $table->integer('hp')->unsigned();
            $table->integer('atk')->unsigned();
            $table->integer('def')->unsigned();
            $table->integer('sp_atk')->unsigned();
            $table->integer('sp_def')->unsigned();
            $table->integer('spe')->unsigned();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stats');
    }
};
