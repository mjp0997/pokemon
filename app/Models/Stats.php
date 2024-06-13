<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Stats extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'stats';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = ['pokemon_id', 'hp', 'atk', 'def', 'sp_atk', 'sp_def', 'spe'];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'pokemon_id' => 'integer',
        'hp' => 'integer',
        'atk' => 'integer',
        'def' => 'integer',
        'sp_atk' => 'integer',
        'sp_def' => 'integer',
        'spe' => 'integer',
    ];

    /**
     * Get the pokemon that owns the Stats
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pokemon(): BelongsTo
    {
        return $this->belongsTo(Pokemon::class, 'pokemon_id');
    }
}
