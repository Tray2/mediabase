<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookFormRequest;
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
    public function __invoke(BookFormRequest $request)
    {
        $valid =  $request->validated();

        if ($request->series_name === 'Standalone') {
            $valid['part'] = null;
        }

        $book = Book::create(array_merge($valid,[
            'genre_id' => $this->getGenreId($request->genre_name),
            'format_id' => $this->getFormatId($request->format_name),
            'series_id' => $this->getSeriesId($request->series_name),
            'publisher_id' => $this->getPublisherId($request->publisher_name),
        ]));

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

    protected function getAuthor($name): array
    {
        $authors = [];

        foreach($name as $author) {
            [$lastName, $firstName] = explode(', ', $author);
            $authors[] =  Author::firstOrCreate(
                ['last_name' => $lastName],
                ['first_name' => $firstName]
            )->value('id');
        }
        return $authors;
    }

    public function getPublisherId($publisherName): mixed
    {
        return Publisher::firstOrCreate(['name' => $publisherName])
            ->value('id');
    }
}
