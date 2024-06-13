<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PokedexController;
use App\Http\Controllers\PokedleController;

Route::get('/', [PokedexController::class, 'index'])->name('pokedex');

Route::get('/dle', [PokedleController::class, 'index'])->name('pokedle');