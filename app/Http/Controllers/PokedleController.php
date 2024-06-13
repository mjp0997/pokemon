<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Pokemon;
use Illuminate\Http\Request;

class PokedleController extends Controller
{
    public function index(Request $request)
    {
        $pokemons = Pokemon::pokedleRelationships()->get();

        $pokemons = $pokemons->map(function (Pokemon $pokemon) {
            return $this->format_pokemon($pokemon);
        });

        return view('pokedle', [
            'pokemons' => $pokemons
        ]);
    }

    public function get_random_pokemon(Request $request)
    {
        $pokemon = Pokemon::pokedleRelationships()->inRandomOrder()->first();

        $pokemon = $this->format_pokemon($pokemon);

        return response()->json($pokemon);
    }

    public function get_all_pokemons(Request $request)
    {
        $pokemons = Pokemon::pokedleRelationships()->get();

        $pokemons = $pokemons->map(function (Pokemon $pokemon) {
            return $this->format_pokemon($pokemon);
        });

        return response()->json($pokemons);
    }

    private function format_pokemon(Pokemon $pokemon): array
    {
        $types = collect($pokemon->types)->map(function ($type) {
            return $type->name;
        })->toArray();

        $height = number_format($pokemon->height / 1000, 1);
        $weight = number_format($pokemon->weight / 1000, 1);

        return [
            'id' => $pokemon->id,
            'sprite' => url("storage/$pokemon->sprite"),
            'name' => $pokemon->name,
            'evolution_stage' => $pokemon->evolution_stage,
            'color' => $pokemon->color->name,
            'type1' => $types[0],
            'type2' => $types[1] ?? null,
            'habitat' => $pokemon->habitat->name,
            'generation' => $pokemon->generation->generation,
            'stats_sum' => $pokemon->stats_sum,
            'height' => $height,
            'weight' => $weight,
        ];
    }
}
