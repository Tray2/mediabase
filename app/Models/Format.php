<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Format extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function setFormatAttribute($value)
    {
        $this->attributes['format'] = ucwords(strtolower($value));
    }

    public function books()
    {
        return $this->hasMany(BookView::class);
    }

    public function records()
    {
        return $this->hasMany(RecordView::class);
    }

    public function media_types()
    {
        return $this->belongsTo(MediaType::class, 'media_type_id');
    }

    public function getCountsAttribute()
    {
        if ($this->books_count) {
            return $this->books_count;
        }

        if ($this->records_count) {
            return $this->records_count;
        }

        if ($this->games_count) {
            return $this->games_count;
        }

        if ($this->movies_count) {
            return $this->movies_count;
        }
    }
}
