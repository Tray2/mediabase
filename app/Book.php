<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\BookCollection;

class Book extends Model
{
    const SERIES = 'series';

    protected $guarded = [];
    
    public function author()
    {
        return $this->belongsToMany(Author::class, 'author_books');
    }

    public function format()
    {
        return $this->belongsTo(Format::class);
    }

    public function genre()
    {
        return $this->belongsTo(Genre::class);
    }

    public function score()
    {
        return $this->hasMany(Score::class);
    }

    public function getScoreAttribute()
    {
        return $this->score()->average('score');
    }

    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = ucwords(strtolower($value));
    }

    public function setSeriesAttribute($value)
    {
        if (!isset($value)) {
            $this->attributes[self::SERIES] = 'Standalone';
        } else {
            $this->attributes[self::SERIES] = ucwords(strtolower($value));
        }
    }

    public function otherInSeries()
    {
        return BookView::whereSeries($this->series)->orderBy('part')->get();
    }

    public function otherBooks()
    {
        $author = AuthorBook::whereBookId($this->id)->first();
        if ($this->series == 'Standalone') {
            return BookView::where('author_id', $author->author_id)
                ->where('title', '!=', $this->title)
                ->orderBy('released')
                ->get();
        }

        return BookView::where('author_id', $author->author_id)->where(self::SERIES, '!=', $this->series)->get();
    }

    public function inCollection()
    {
        return(BookCollection::whereBookId($this->id)->where('user_id', Auth::user()->id)->count());
    }

    public function isRead()
    {
        return BookRead::whereBookId($this->id)->where('user_id', Auth::user()->id)->count();
    }
}
