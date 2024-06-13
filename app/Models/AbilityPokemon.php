<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class AbilityPokemon extends Pivot
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ability_pokemon';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_hidden' => 'boolean',
    ];
}
