<?php

use App\Http\Controllers\PokedleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('/pokemons')->group(function () {
    Route::get('/', [PokedleController::class, 'get_all_pokemons'])->name('api.get-all-pokemons');
    Route::get('/random', [PokedleController::class, 'get_random_pokemon'])->name('api.get-random');
});