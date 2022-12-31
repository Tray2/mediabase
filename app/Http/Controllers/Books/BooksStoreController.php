<?php

namespace App\Http\Controllers\Books;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookFormRequest;
use App\Models\Book;
use App\Services\ForeignKeyService;

class BooksStoreController extends Controller
{
    public function __invoke(BookFormRequest $request, ForeignKeyService $foreignKeyService)
    {
        $valid = $request->validated();

        if ($request->series_name === 'Standalone') {
            $valid['part'] = null;
        }

        $book = Book::create(array_merge($valid, [
            'genre_id' => $foreignKeyService->getGenreId($request->genre_name, 'book'),
            'format_id' => $foreignKeyService->getFormatId($request->format_name, 'book'),
            'series_id' => $foreignKeyService->getSeriesId($request->series_name),
            'publisher_id' => $foreignKeyService->getPublisherId($request->publisher_name),
        ]));

        $book->authors()->attach($foreignKeyService->getAuthorIds($request->author));

        return redirect(route('books.index'));
    }
}
