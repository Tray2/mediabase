<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    use HasFactory;

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
