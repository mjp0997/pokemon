document.addEventListener('DOMContentLoaded', () => {
   const pokedexList = document.querySelector('#pokedex-list');

   let currentIndex = 0;

   window.addEventListener('keydown', (e) => {
      if (!['ArrowDown', 'ArrowUp'].includes(e.key)) return;
      
      e.preventDefault();

      const entries = pokedexList.children;

      if (e.key == 'ArrowDown') {
         if (currentIndex < entries.length - 1) {

            entries[currentIndex].classList.remove('active');

            currentIndex += 1;

            entries[currentIndex].classList.add('active');
         }
      }

      if (e.key == 'ArrowUp') {
         if (currentIndex >= 1) {

            entries[currentIndex].classList.remove('active');

            currentIndex -= 1;

            entries[currentIndex].classList.add('active');
         }
      }

      // Si el elemento no se ve o se ve parcialmente, se mueve el scroll
      entries[currentIndex].scrollIntoView({
         behavior: 'instant',
         block: 'nearest'
      });

      updateCurrentPokemon(entries[currentIndex]);
   });

   const pokedexEntries = document.querySelectorAll('.pokedex-list-element');

   pokedexEntries.forEach((entry, entryIndex) => {
      entry.addEventListener('click', () => {
         pokedexEntries[currentIndex].classList.remove('active');
         
         currentIndex = entryIndex;

         entry.classList.add('active');

         entry.scrollIntoView({
            behavior: 'instant',
            block: 'nearest'
         });

         updateCurrentPokemon(entry);
      });
   });

   const getPokemonData = (htmlDomEntry) => {
      const pokemonJson = htmlDomEntry.dataset.json;

      return JSON.parse(pokemonJson);
   }

   const updateCurrentPokemon = (htmlDomEntry) => {
      const name = document.querySelector('#pokemon-name');
      const img = document.querySelector('#pokemon-img');
      const types = document.querySelector('#pokemon-type');
      const generation = document.querySelector('#pokemon-generation');
      const habitat = document.querySelector('#pokemon-habitat');
      const color = document.querySelector('#pokemon-color');

      const hp = document.querySelector('#pokemon-hp');
      const atk = document.querySelector('#pokemon-atk');
      const def = document.querySelector('#pokemon-def');
      const sp_atk = document.querySelector('#pokemon-sp_atk');
      const sp_def = document.querySelector('#pokemon-sp_def');
      const spe = document.querySelector('#pokemon-spe');

      const pokemon = getPokemonData(htmlDomEntry);

      name.textContent = pokemon.name;

      img.src = pokemon.sprite;
      img.alt = pokemon.name

      types.innerHTML = pokemon.types.map(type => `<span class='pokedex-entry-type' style='background-color: ${type.color};'>${type.name}</span>`).join('');

      generation.textContent = pokemon.generation;

      habitat.textContent = pokemon.habitat;

      color.textContent = pokemon.color;

      hp.textContent = pokemon.stats.hp.value;
      atk.textContent = pokemon.stats.atk.value;
      def.textContent = pokemon.stats.def.value;
      sp_atk.textContent = pokemon.stats.sp_atk.value;
      sp_def.textContent = pokemon.stats.sp_def.value;
      spe.textContent = pokemon.stats.spe.value;
   }
});