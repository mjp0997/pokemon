<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Pokemon;
use Illuminate\Http\Request;

class PokedexController extends Controller
{
    public function index(Request $request)
    {
        $pokemons = Pokemon::with('types', 'stats', 'habitat', 'color', 'abilities')->get();

        $pokemons = collect($pokemons)->map(function (Pokemon $pokemon) {
            return $this->format_pokemon($pokemon);
        })->toArray();

        return view('pokedex', [
            'pokemons' => $pokemons,
        ]);
    }

    private function format_pokemon(Pokemon $pokemon): array
    {
        $types = collect($pokemon->types)->map(function ($type) {
            return [
                'name' => $type->name,
                'color' => $type->color,
            ];
        })->toArray();

        $stats = [
            'hp' => [
                'stat' => 'hp',
                'value' => $pokemon->stats->hp
            ],
            'atk' => [
                'stat' => 'atk',
                'value' => $pokemon->stats->atk
            ],
            'def' => [
                'stat' => 'def',
                'value' => $pokemon->stats->def
            ],
            'sp_atk' => [
                'stat' => 'sp atk',
                'value' => $pokemon->stats->sp_atk
            ],
            'sp_def' => [
                'stat' => 'sp def',
                'value' => $pokemon->stats->sp_def
            ],
            'spe' => [
                'stat' => 'spe',
                'value' => $pokemon->stats->spe
            ],
        ];

        return [
            'name' => $pokemon->name,
            'api_id' => $pokemon->api_id,
            'sprite' => url("storage/$pokemon->sprite"),
            'types' => $types,
            'stats' => $stats,
            'habitat' => $pokemon->habitat->name ?? '-',
            'generation' => $pokemon->generation->generation,
            'color' => $pokemon->color->name,
        ];
    }
}
