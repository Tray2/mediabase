<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BookCollection extends Model
{
    protected $guarded = [];

    public function book()
    {
        return $this->hasOne(Book::class, 'id');
    }
}
