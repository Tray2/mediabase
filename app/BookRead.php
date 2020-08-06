<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BookRead extends Model
{
    protected $guarded = [];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
