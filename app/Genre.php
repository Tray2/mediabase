<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    protected $guarded = [];

    public function setGenreAttribute($value)
    {
        $this->attributes['genre'] = ucwords(strtolower($value));
    }

    public function books()
    {
        return $this->hasMany(BookView::class);
    }
}

