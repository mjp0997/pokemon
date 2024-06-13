document.addEventListener('DOMContentLoaded', async () => {
   const randomPokemon = await getRandomPokemon();

   const form = document.querySelector('#pokedle-form');
   const input = document.querySelector('#pokedle-input');

   const autofill = document.querySelector('#pokedle-autofill');
   let options = getOptions();
   const noMatch = document.querySelector('#pokedle-no-match');

   const list = document.querySelector('#pokedle-list');
   const listHeader = document.querySelector('#pokedle-list .pokedle-header');

   const modalBackdrop = document.querySelector('#modal-backdrop');
   const modal = document.querySelector('#modal');
   const guessedImg = document.querySelector('#guessed-img');
   const guessedName = document.querySelector('#guessed-name');
   const guessedAttemps = document.querySelector('#guessed-attemps');

   const playAgain = document.querySelector('#play-again');

   let attemps = 0;

   form.addEventListener('submit', e => {
      e.preventDefault();

      const { value } = input;

      if (value.trim() == '') {
         return;
      }

      const firstOption = document.querySelector('.pokedle-option.show');

      handleOptionClick(firstOption);
   });

   input.addEventListener('input', (e) => {
      const { value } = input;

      noMatch.classList.remove('show');

      if (value.trim() == '') {
         autofill.classList.remove('show');
         return;
      }

      autofill.classList.add('show');

      let hasMatch = false;

      options.forEach(option => {
         const pokemonName = option.children[1].textContent;

         if (pokemonName.includes(value)) {
            option.classList.add('show');
            hasMatch = true;
         } else {
            option.classList.remove('show');
         }
      });

      if (!hasMatch) {
         noMatch.classList.add('show');
      }
   });

   input.addEventListener('focus', () => {
      const { value } = input;

      if (value.trim() != '') {
         autofill.classList.add('show');
      }
   });

   window.addEventListener('click', (e) => {
      const { value } = input;

      if (!form.contains(e.target) && value.trim() != '') {
         autofill.classList.remove('show');
      }
   })

   options.forEach(option => {
      option.addEventListener('click', () => handleOptionClick(option));
   });

   playAgain.addEventListener('click', () => location.reload());

   const handleOptionClick = (option) => {
      input.value = '';
      autofill.classList.remove('show');

      const dataPokemon = option.dataset.pokemon;
      const selectedPokemon = JSON.parse(dataPokemon);

      if (!list.classList.contains('show')) {
         list.classList.add('show');
      }

      attemps += 1;
      
      generateRow(selectedPokemon);
      option.remove();
      options = getOptions();
   }

   const generateRow = (pokemon) => {
      const row = document.createElement('li');
      row.classList.add('pokedle-element', 'pokedle-try');

      const { type1, type2, generation, habitat, color, evolutionStage, height, weight } = pokemonMatch(pokemon);

      row.innerHTML = `
         <div class="pokedle-img"><img src='${pokemon.sprite}' alt='${pokemon.name}'></div>
         <div class="${type1}"><p>${pokemon.type1}</p></div>
         <div class="${type2}"><p>${pokemon.type2 ?? 'none'}</p></div>
         <div class="${generation}"><p>${pokemon.generation}</p></div>
         <div class="${habitat}"><p>${pokemon.habitat ?? 'unknown'}</p></div>
         <div class="${color}"><p>${pokemon.color}</p></div>
         <div class="${evolutionStage}"><p>${pokemon.evolution_stage}</p></div>
         <div class="${height}"><p>${pokemon.height}m</p></div>
         <div class="${weight}"><p>${pokemon.weight}Kg</p></div>
      `;

      listHeader.after(row);

      if (pokemon.id == randomPokemon.id) {
         modalBackdrop.classList.add('show');
         modal.classList.add('show');

         guessedImg.src = randomPokemon.sprite;
         guessedName.textContent = randomPokemon.name;
         guessedAttemps.textContent = attemps;
      }
   }

   const pokemonMatch = (pokemon) => {
      let type1 = 'red';

      if (pokemon.type1 == randomPokemon.type1) {
         type1 = 'green';
      } else if (pokemon.type1 == randomPokemon.type2) {
         type1 = 'yellow';
      }

      let type2 = 'red';

      if (pokemon.type2 == randomPokemon.type2) {
         type2 = 'green';
      } else if (pokemon.type2 == randomPokemon.type1) {
         type2 = 'yellow';
      }

      let generation = 'up';

      if (pokemon.generation == randomPokemon.generation) {
         generation = 'green';
      } else if (pokemon.generation > randomPokemon.generation) {
         generation = 'down';
      }

      let habitat = 'red';

      if (pokemon.habitat == randomPokemon.habitat) {
         habitat = 'green';
      }

      let color = 'red';

      if (pokemon.color == randomPokemon.color) {
         color = 'green';
      }

      let evolutionStage = 'up';

      if (pokemon.evolution_stage == randomPokemon.evolution_stage) {
         evolutionStage = 'green';
      } else if (pokemon.evolution_stage > randomPokemon.evolution_stage) {
         evolutionStage = 'down';
      }

      let height = 'up';

      if (Number(pokemon.height) == Number(randomPokemon.height)) {
         height = 'green';
      } else if (Number(pokemon.height) > Number(randomPokemon.height)) {
         height = 'down';
      }

      let weight = 'up';

      if (Number(pokemon.weight) == Number(randomPokemon.weight)) {
         weight = 'green';
      } else if (Number(pokemon.weight) > Number(randomPokemon.weight)) {
         weight = 'down';
      }

      return {
         type1,
         type2,
         generation,
         habitat,
         color,
         evolutionStage,
         height,
         weight,
      }
   }
});

const getRandomPokemon = async () => {
   const response = await fetch(`${domain}/api/pokemons/random`);

   const data = await response.json();

   return data;
}

const getOptions = () => document.querySelectorAll('.pokedle-option');