<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ability extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'abilities';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = ['name'];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
    ];

    /**
     * The pokemons that belong to the Ability
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function pokemons(): BelongsToMany
    {
        return $this->belongsToMany(Pokemon::class, 'ability_pokemon', 'ability_id', 'pokemon_id')
            ->withPivot('is_hidden')
            ->withTimestamps()
            ->using(AbilityPokemon::class);
    }
}
