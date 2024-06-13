<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <meta http-equiv='X-UA-Compatible' content='ie=edge'>
    <title>Pokedex</title>

    <link rel='stylesheet' href='{{ asset('css/index.css') }}'>
    <link rel='stylesheet' href='{{ asset('css/components/pokedex/index.css') }}'>
</head>
<body>
    <div class="pokedex-body">
        <div class="pokeball">
            <div class="pokeball-inner">
                <div class="pokeball-lid top"></div>
    
                <div class="pokeball-center">
                    <div class='pokeball-center-inner'>
                        <div class='pokeball-center-pin'></div>
                    </div>
                </div>
    
                <div class="pokeball-lid bottom"></div>
            </div>
        </div>

        <div class='pokedex'>
            <div class='pokedex-content'>
                <div class='pokedex-list-rail' id="pokedex-rail">
                    <div class='pokedex-list' id="pokedex-list">
                        @foreach ($pokemons as $pokemon)
                            <div class='pokedex-list-element @if ($loop->first) active @endif' data-json="{{ json_encode($pokemon) }}">
                                <p>No {{ str_pad($pokemon['api_id'], 4, '0', STR_PAD_LEFT) }}</p>
                                
                                <p>{{ $pokemon['name'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class='pokedex-entry'>
                    <div class='pokedex-entry-img'>
                        <img id="pokemon-img" src='{{ $pokemons[0]['sprite'] }}' alt='{{ $pokemons[0]['name'] }}'>
                    </div>

                    <div class='pokedex-entry-data'>
                        <p class="pokedex-entry-name" id="pokemon-name">{{ $pokemons[0]['name'] }}</p>

                        <p class="pokedex-entry-types" id="pokemon-type">
                            @foreach ($pokemons[0]['types'] as $type)
                                <span class="pokedex-entry-type" style="background-color: {{ $type['color'] }}">{{ $type['name'] }}</span>
                            @endforeach
                        </p>
                        
                        <div class='pokedex-entry-row'>
                            <div class='pokedex-entry-column'>
                                <div class="pokedex-entry-info">
                                    <p>Generation:</p>
                                    <p id="pokemon-generation">{{ $pokemons[0]['generation'] }}</p>
                                </div>
                                
                                <div class="pokedex-entry-info">
                                    <p>Habitat:</p>
                                    <p id="pokemon-habitat">{{ $pokemons[0]['habitat'] }}</p>
                                </div>
                                
                                <div class="pokedex-entry-info">
                                    <p>Color:</p>
                                    <p id="pokemon-color">{{ $pokemons[0]['color'] }}</p>
                                </div>
                            </div>

                            <div class='pokedex-entry-column'>
                                <div class="pokedex-entry-stats">
                                    <table>
                                        <tbody>
                                            <tr>
                                                <td colspan="2" class="pokedex-entry-colspan">STATS:</td>
                                            </tr>
                                            @foreach ($pokemons[0]['stats'] as $stat => $data)
                                                <tr>
                                                    <td class="pokedex-entry-stat">{{ $data['stat'] }}</td>
                                                    <td id="pokemon-{{$stat}}" class="pokedex-entry-value">{{ $data['value'] }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src='{{ asset('js/pokedex.js') }}'></script>
</body>
</html>