<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class MovieShowView extends Model
{
    public function actors(): BelongsToMany
    {
        return $this->belongsToMany(Actor::class, 'actor_movie', 'movie_id');
    }

}
