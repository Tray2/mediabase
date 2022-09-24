<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Book;
use App\Models\Format;
use App\Models\Genre;
use App\Models\Publisher;
use App\Models\Series;
use App\Rules\Isbn;
use App\Rules\MinWords;
use App\Rules\RequiredIfNotStandalone;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BooksStoreController extends Controller
{
    public function __invoke(Request $request)
    {
        $valid =  $request->validate([
           'title' => 'required',
            'published_year' => ['required', 'numeric', 'min_digits:4', 'max_digits:4', 'between:1800,' . Carbon::now()->addYear(1)->year],
            'isbn' => ['required', new Isbn(),],
            'blurb' => ['required', new MinWords(3)],
            'author' => 'required',
            'genre_name' => 'required',
            'format_name' => 'required',
            'series_name' => 'required',
            'publisher_name' => 'required',
            'part' => [new RequiredIfNotStandalone($request->series_name),],
        ]);

        if ($request->series_name === 'Standalone') {
            $request->part = null;
        }

        $book =Book::create([
            'title' => $request->title,
            'published_year' => $request->published_year,
            'isbn' => $request->isbn,
            'part' => $request->part,
            'blurb' => $request->blurb,
            'genre_id' => $this->getGenreId($request->genre_name),
            'format_id' => $this->getFormatId($request->format_name),
            'series_id' => $this->getSeriesId($request->series_name),
            'publisher_id' => $this->getPublisherId($request->publisher_name),
        ]);

        $book->authors()->attach($this->getAuthor($request->author));
        return redirect(route('books.index'));
    }

    protected function getSeriesId($seriesName)
    {
        return Series::firstOrCreate(['name' => $seriesName])
                ->value('id');
    }

    protected function getFormatId($formatName)
    {
        return Format::firstOrCreate(['name' => $formatName])
                ->value('id');
    }

    protected function getGenreId($genreName)
    {
        return Genre::FirstOrCreate(['name' => $genreName])
                ->value('id');
    }

    protected function getAuthor($name): Author
    {
        [$lastName, $firstName] = explode(', ', $name);
        return Author::firstOrCreate(
            ['last_name' => $lastName],
            ['first_name' => $firstName]
        );
    }

    public function getPublisherId($publisherName): mixed
    {
        return Publisher::firstOrCreate(['name' => $publisherName])
            ->value('id');
    }
}
