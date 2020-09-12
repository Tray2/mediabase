<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookCollection extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function book()
    {
        return $this->hasOne(Book::class, 'id');
    }
}
