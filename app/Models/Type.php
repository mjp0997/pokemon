<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Type extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'types';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = ['name', 'color'];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
    ];

    /**
     * The pokemons that belong to the Type
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function pokemons(): BelongsToMany
    {
        return $this->belongsToMany(Pokemon::class, 'pokemon_type', 'type_id', 'pokemon_id')->withTimestamps();
    }
}
