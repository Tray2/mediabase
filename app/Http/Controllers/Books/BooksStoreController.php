<?php

namespace App\Http\Controllers\Books;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookFormRequest;
use App\Models\Book;

class BooksStoreController extends Controller
{
    public function __invoke(BookFormRequest $request)
    {
        $valid = $request->validated();

        if ($request->series_name === 'Standalone') {
            $valid['part'] = null;
        }

        $book = Book::create(array_merge($valid, [
            'genre_id' => $request->getGenreId(),
            'format_id' => $request->getFormatId(),
            'series_id' => $request->getSeriesId(),
            'publisher_id' => $request->getPublisherId(),
        ]));

        $book->authors()->attach($request->getAuthor());

        return redirect(route('books.index'));
    }
}
