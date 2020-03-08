<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Artist extends Model
{
    protected $guarded = [];

    public function records()
    {
        return $this->hasMany(Record::Class);
    }
}
