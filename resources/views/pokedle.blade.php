<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <meta http-equiv='X-UA-Compatible' content='ie=edge'>
    <title>Pokedle</title>

    <link rel='stylesheet' href='{{ asset('css/index.css') }}'>
    <link rel='stylesheet' href='{{ asset('css/components/pokedle/index.css') }}'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <div class="pokedle-container">
        <div class="pokedle-help">
            <p>Indicators</p>

            <ul class="pokedle-indicators">
                <li class="green">
                    <p>Correct</p>
                </li>
                <li class="yellow">
                    <p>Correct but in wrong position</p>
                </li>
                <li class="red">
                    <p>Wrong</p>
                </li>
                <li class="down">
                    <p>Lesser than given</p>
                </li>
                <li class="up">
                    <p>Greater than given</p>
                </li>
            </ul>
        </div>

        <form class="pokedle-form" autocomplete="off" id="pokedle-form">
            <input type='text' id="pokedle-input" placeholder="Search for a pokémon">

            <button type='submit'>
                <i class="fa-solid fa-play"></i>
            </button>

            <div class='pokedle-autofill' id="pokedle-autofill">
                <p id="pokedle-no-match">No match found</p>

                @foreach ($pokemons as $pokemon)
                    <x-pokedle-autofill-option :sprite="$pokemon['sprite']" :name="$pokemon['name']" :pokemon="$pokemon" />
                @endforeach
            </div>
        </form>

        <ul class="pokedle-list" id="pokedle-list">
            <li class="pokedle-element pokedle-header">
                <div><p>Pokémon</p></div>
                <div><p>Type 1</p></div>
                <div><p>Type 2</p></div>
                <div><p>Gen.</p></div>
                <div><p>Habitat</p></div>
                <div><p>Color</p></div>
                <div><p>Evol. Stage</p></div>
                <div><p>height</p></div>
                <div><p>weight</p></div>
            </li>
        </ul>
    </div>

    <div class='modal-backdrop' id="modal-backdrop"></div>

    <div class='pokedle-modal-container' id="modal">
        <div class='pokedle-modal'>
            <p>Good job!</p>

            <p>You guessed:</p>

            <div class="pokedle-guessed">
                <div class="img">
                    <img src='' alt='' id="guessed-img">
                </div>

                <div class="data">
                    <p id="guessed-name"></p>

                    <p>Number of attemps: <span id="guessed-attemps"></span></p>
                </div>
            </div>

            <button type="button" id="play-again">Play again</button>
        </div>
    </div>

    <script>
        const domain = "{{ url('') }}";
    </script>

    <script src='{{ asset('js/pokedle.js') }}'></script>
</body>
</html>