<?php

namespace App\Http\Controllers\Books;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookFormRequest;
use App\Models\Book;
use App\Services\ForeignKeyService;

class BooksUpdateController extends Controller
{
    public function __invoke(Book $book, BookFormRequest $request, ForeignKeyService $foreignKeyService)
    {
        $valid = $request->validated();

        if ($request->series_name === 'Standalone') {
            $valid['part'] = null;
        }

        $book->update(array_merge($valid, [
            'genre_id' => $foreignKeyService->getGenreId($request->genre_name, 'book'),
            'format_it' => $foreignKeyService->getFormatId($request->format_name, 'book'),
            'series_id' => $foreignKeyService->getSeriesId($request->series_name),
            'publisher_id' => $foreignKeyService->getPublisherId($request->publisher_name),
        ]));

        $book->authors()->sync($foreignKeyService->getAuthorIds($request->author));

        return redirect(route('books.index'));
    }
}
