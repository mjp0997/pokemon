<?php

namespace App\Models;

use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pokemon extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pokemons';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = ['generation_id', 'habitat_id', 'color_id', 'name', 'evolution_stage', 'api_id', 'sprite', 'height', 'weight'];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'evolution_stage' => 'integer',
        'generation_id' => 'integer',
        'habitat_id' => 'integer',
        'color_id' => 'integer',
        'api_id' => 'integer',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['stats_sum'];

    function statsSum(): Attribute
    {
        $stats = $this->stats;

        $total = 0;

        $total += $stats->hp;
        $total += $stats->atk;
        $total += $stats->def;
        $total += $stats->sp_atk;
        $total += $stats->sp_def;
        $total += $stats->spe;

        return Attribute::make(
            get: fn () => $total,
        );
    }

    /**
     * Scope a query to only include relationships used in Pokedle
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePokedleRelationships(Builder $query)
    {
        return $query->with(['types', 'stats', 'habitat', 'color', 'abilities']);
    }

    /**
     * Get the generation that owns the Pokemon
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function generation(): BelongsTo
    {
        return $this->belongsTo(Generation::class, 'generation_id');
    }

    /**
     * Get the habitat that owns the Pokemon
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function habitat(): BelongsTo
    {
        return $this->belongsTo(Habitat::class, 'habitat_id');
    }

    /**
     * Get the color that owns the Pokemon
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function color(): BelongsTo
    {
        return $this->belongsTo(Color::class, 'color_id');
    }

    /**
     * Get the stats associated with the Pokemon
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function stats(): HasOne
    {
        return $this->hasOne(Stats::class, 'pokemon_id');
    }

    /**
     * The types that belong to the Pokemon
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function types(): BelongsToMany
    {
        return $this->belongsToMany(Type::class, 'pokemon_type', 'pokemon_id', 'type_id')
            ->withPivot('id')
            ->withTimestamps()
            ->orderByPivot('id');
    }

    /**
     * The abilities that belong to the Pokemon
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function abilities(): BelongsToMany
    {
        return $this->belongsToMany(Ability::class, 'ability_pokemon', 'pokemon_id', 'ability_id')
            ->withPivot('is_hidden')
            ->withTimestamps()
            ->using(AbilityPokemon::class);
    }
}
