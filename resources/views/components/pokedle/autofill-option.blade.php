<button type="button" class="pokedle-option" data-pokemon='{{ json_encode($pokemon) }}'>
    <div>
        <img src='{{ $sprite }}' alt='{{ $name }}' loading="lazy">
    </div>

    <p>{{ $name }}</p>
</button>