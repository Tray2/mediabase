<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    protected $guarded = [];

    public function getNameAttribute()
    {
        return $this->last_name . ', ' . $this->first_name;
    }

    public function books()
    {
        return $this->belongsToMany(Book::class, 'author_books');
    }
}
