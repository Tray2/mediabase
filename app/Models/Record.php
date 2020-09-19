<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    use HasFactory;

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

    public function getScoreAttribute()
    {
        $id = MediaType::where('media', 'Records')->pluck('id')->first();
        return Score::where('media_type_id', $id)->where('item_id', $this->id)->average('score');
    }
}
