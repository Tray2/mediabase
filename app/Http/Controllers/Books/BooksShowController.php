<?php

namespace App\Http\Controllers\Books;

use App\Http\Controllers\Controller;
use App\Models\BookIndexView;
use App\Models\BookShowView;

class BooksShowController extends Controller
{
    public function __invoke(BookShowView $bookShowView)
    {
        $otherBooksQuery = BookIndexView::query();

        if($bookShowView->isStandalone()) {
            $otherBooksQuery->whereNot('book_id', $bookShowView->book_id);
        } else {
            $otherBooksQuery->whereNot('series_id', $bookShowView->series_id);
        }

        return view('books.show')->with([
            'book' => $bookShowView,
            'booksInSeries' => BookIndexView::query()
                ->where('series_id', $bookShowView->series_id)
                ->whereNot('book_id', $bookShowView->book_id)
                ->whereNot('series', 'Standalone')
                ->orderBy('part')
                ->get(),
            'otherBooks' => $otherBooksQuery
                ->whereIn('author_id', explode(',', $bookShowView->author_id))
                ->orderBy('series_started')
                ->orderBy('published_year')
                ->get()
        ]);
    }
}
