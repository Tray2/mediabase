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

    public function media_types()
    {
        return $this->belongsTo(MediaType::class, 'media_type_id');
    }
}
