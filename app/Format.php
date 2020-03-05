<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Format extends Model
{
    protected $guarded = [];

    public function setFormatAttribute($value)
    {
        $this->attributes['format'] = ucwords(strtolower($value));
    }

    public function books()
    {
        return $this->hasMany(BookView::class);
    }
}
