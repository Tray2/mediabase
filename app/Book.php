<?php

namespace App;

use App\Http\Requests\BookFormRequest;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\BookCollection;

class Book extends Model
{
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
            $this->attributes['series'] = 'Standalone';
        } else {
            $this->attributes['series'] = ucwords(strtolower($value));
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

        return BookView::where('author_id', $author->author_id)->where('series', '!=', $this->series)->get();
    }

    public function inCollection()
    {
        return(BookCollection::whereBookId($this->id)->where('user_id', Auth::user()->id)->count());
    }

    public function isRead()
    {
        return BookRead::whereBookId($this->id)->where('user_id', Auth::user()->id)->count();
    }

    /**
     * @param BookFormRequest $request
     */
    public function addAuthors(BookFormRequest $request): void
    {
        if (isset($request->additional_authors)) {
            $authors = array_merge([$request->author_id], $request->additional_authors);
        } else {
            $authors = [$request->author_id];
        }
        foreach ($authors as $author) {
            AuthorBook::create([
                'author_id' => $author,
                'book_id' => $this->id
            ]);
        }
    }

    public function addToCollection(): void
    {
        BookCollection::create([
            'book_id' => $this->id,
            'user_id' => Auth::user()->id
        ]);
    }

    public function markAsRead(Request $request): void
    {
        if (isset($request->read)) {
            BookRead::create([
                'book_id' => $this->id,
                'user_id' => Auth::user()->id
            ]);
        }
    }


}
