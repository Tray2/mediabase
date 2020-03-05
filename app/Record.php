<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    protected $guarded = [];

    public function tracks()
    {
        return $this->hasMany(Track::class);
    }

    public function artist()
    {
        return $this->belongsTo(Artist::class);
    }

    public function format()
    {
        return $this->belongsTo(Format::class);
    }

    public function genre()
    {
        return $this->belongsTo(Genre::class);
    }
}
