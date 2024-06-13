<?php

namespace App\Console\Commands;

use App\Models\Ability;
use App\Models\Generation;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

use App\Models\Pokemon;
use App\Models\Stats;
use App\Models\Type;
use App\Models\Color;
use App\Models\Habitat;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class GetPokemons extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pokemons:get';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed pokemons database from poke_api v2';

    private $types_colors = [];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->call('pokemon:sprites:clean');

        $api_route = 'https://pokeapi.co/api/v2/pokemon-species';

        $this->newLine();
        $this->info("Iniciando petición al api $api_route");
        
        $response = Http::get("$api_route?limit=2000");
        
        $data = $response->json();

        $pokemon_species = $data['results'];

        $this->newLine();
        $this->info('Respuesta obtenida');

        $this->newLine();
        $this->info('Obteniendo datos adicionales');

        $this->get_colors_types();

        $format_bar = $this->output->createProgressBar(count($pokemon_species));
        $format_bar->setFormat(" %current%/%max% [%bar%] %percent:3s%% | %message%");
        $format_bar->setMessage('pokemon');

        $this->newLine();
        $this->info('Iniciando peticiones por pokémon.');
        $this->newLine();
        $format_bar->start();

        foreach ($pokemon_species as $specie) {
            $format_bar->setMessage($specie['name']);

            $specie_response = Http::get($specie['url']);

            $specie_data = $specie_response->json();

            $specie_id = $specie_data['id'];

            $pokemon_response = Http::get("https://pokeapi.co/api/v2/pokemon/$specie_id");

            $pokemon = $pokemon_response->json();

            $this->create_pokemon($pokemon, $specie_data);

            $format_bar->advance();
        }

        $this->newLine(2);
        $this->info('Data insertada correctamente.');
        $this->newLine();

        return Command::SUCCESS;
    }

    private function create_pokemon(array $pokemon_data, array $specie_data): Pokemon
    {
        $generation = $this->get_generation($specie_data['generation']['name']);

        $habitat = $this->get_habitat($specie_data['habitat']['name'] ?? null);

        $color = $this->get_color($specie_data['color']['name']);

        $sprite_url = $this->save_sprite($pokemon_data['sprites']['front_default'], $specie_data['name']);

        $pokemon = Pokemon::firstOrCreate([
            'api_id' => $pokemon_data['id'],
        ], [
            'name' => $specie_data['name'],
            'evolution_stage' => $this->get_evolution_stage($specie_data['name'], $specie_data['evolution_chain']['url']),
            'generation_id' => $generation->id,
            'habitat_id' => $habitat?->id,
            'color_id' => $color->id,
            'sprite' => $sprite_url,
            'height' => $pokemon_data['height'] * 100,
            'weight' => $pokemon_data['weight'] * 100,
        ]);
        
        foreach ($pokemon_data['types'] as $type_data) {
            $type_name = $type_data['type']['name'];

            $type = $this->get_type($type_name);

            $pokemon->types()->attach($type->id);
        }

        $this->create_stats($pokemon_data['stats'], $pokemon->id);

        foreach ($pokemon_data['abilities'] as $ability_data) {
            $ability_name = $ability_data['ability']['name'];

            $ability = $this->get_ability($ability_name);

            $is_hidden = $ability_data['is_hidden'];

            $pokemon->abilities()->attach($ability->id, ['is_hidden' => $is_hidden]);
        }

        return $pokemon;
    }

    private function get_type(string $type_name): Type
    {
        $type = Type::firstOrCreate([
            'name' => $type_name,
        ], [
            'color' => $this->types_colors[$type_name],
        ]);

        return $type;
    }

    private function create_stats(array $stats_data, int $pokemon_id): void
    {
        $stats = [];

        foreach ($stats_data as $stat) {
            $stats[$stat['stat']['name']] = $stat['base_stat'];
        }

        $stats_array = [
            'pokemon_id' => $pokemon_id,
            'hp' => $stats['hp'],
            'atk' => $stats['attack'],
            'def' => $stats['defense'],
            'sp_atk' => $stats['special-attack'],
            'sp_def' => $stats['special-defense'],
            'spe' => $stats['speed'],
        ];

        $stats = new Stats($stats_array);
        $stats->save();
    }

    private function get_ability(string $ability_name): Ability
    {
        $ability = Ability::firstOrCreate([
            'name' => $ability_name
        ]);

        return $ability;
    }

    private function get_generation(string $generation_name): Generation
    {
        $numbers = [
            'i' => 1,
            'ii' => 2,
            'iii' => 3,
            'iv' => 4,
            'v' => 5,
            'vi' => 6,
            'vii' => 7,
            'viii' => 8,
            'ix' => 9,
            'x' => 10,
        ];

        $generation_number = explode('-', $generation_name)[1];

        $generation = Generation::firstOrCreate([
            'generation' => $numbers[$generation_number]
        ]);

        return $generation;
    }

    private function get_color(string $color_name): Color
    {
        $color = Color::firstOrCreate([
            'name' => $color_name,
        ]);

        return $color;
    }

    private function get_habitat(?string $habitat_name): Habitat
    {
        $habitat = Habitat::firstOrCreate([
            'name' => $habitat_name,
        ]);

        return $habitat;
    }

    private function save_sprite(string $sprite_url, $pokemon_name): string
    {
        $contents = file_get_contents($sprite_url);

        $file_original_name = basename($sprite_url);
        $extension = explode('.', $file_original_name)[1];

        $file_name = Carbon::now()->valueOf()."_$pokemon_name.$extension";

        $file_uri = "pokemon/$file_name";

        Storage::put("public/$file_uri", $contents);

        return $file_uri;
    }

    private function get_colors_types(): void
    {
        $this->types_colors = File::json(public_path('data/types_colors.json'));
    }

    private function get_evolution_stage(string $pokemon_name, string $evolution_chain_url): int
    {
        $response = Http::get($evolution_chain_url);
        
        $data = $response->json();

        $chain = $data['chain'];

        return $this->get_recursive_evolution_stage($pokemon_name, $chain);
    }

    private function get_recursive_evolution_stage(string $pokemon_name, array $evolution_chain, int $current_stage = 1): ?int
    {
        if (!isset($evolution_chain['species']['name'])) {
            return null;
        }

        if ($evolution_chain['species']['name'] == $pokemon_name) {
            return $current_stage;
        }

        if (count($evolution_chain['evolves_to']) == 0) {
            return null;
        }

        if (count($evolution_chain['evolves_to']) == 1) {
            return $this->get_recursive_evolution_stage($pokemon_name, $evolution_chain['evolves_to'][0], $current_stage + 1);
        }

        if (count($evolution_chain['evolves_to']) > 1) {
            $current_position = 0;

            $found_stage = null;

            do {
                $found_stage = $this->get_recursive_evolution_stage($pokemon_name, $evolution_chain['evolves_to'][$current_position], $current_stage + 1);
                if ($found_stage == null) {
                    $current_position++;
                }
            } while ($found_stage == null && $current_position < count($evolution_chain['evolves_to']));

            return $found_stage;
        }

        return null;
    }
}
