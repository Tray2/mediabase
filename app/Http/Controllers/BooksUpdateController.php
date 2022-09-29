<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookFormRequest;
use App\Models\Book;


class BooksUpdateController extends Controller
{
    public function __invoke(Book $book, BookFormRequest $request)
    {
        $valid =  $request->validated();

        if ($request->series_name === 'Standalone') {
            $valid['part'] = null;
        }

        $book->update(array_merge($valid,[
            'genre_id' => $request->getGenreId(),
            'format_it' => $request->getFormatId(),
            'series_id' => $request->getSeriesId(),
            'publisher_id' => $request->getPublisherId(),
        ]));

        $book->authors()->sync($request->getAuthor());
        return redirect(route('books.index'));
    }
}
