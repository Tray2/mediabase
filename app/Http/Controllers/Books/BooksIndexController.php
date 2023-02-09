<?php

namespace App\Http\Controllers\Books;

use App\Http\Controllers\Controller;
use App\Models\BookIndexView;
use Illuminate\Http\Request;

class BooksIndexController extends Controller
{
    public function __invoke(Request $request)
    {
        return view('books.index')
            ->with([
                'books' => BookIndexView::query()
                    ->when($request->authors, function ($query) use ($request) {
                        $query->whereIn('author_id', array_map('intval', explode(',', $request->authors)));
                    })
                    ->when($request->published, function ($query) use ($request) {
                        $query->where('published_year', $request->published);
                    })
                    ->when($request->genre, function ($query) use ($request) {
                        $query->where('genre', $request->genre);
                    })
                    ->when($request->format, function ($query) use ($request) {
                        $query->where('format', $request->format);
                    })
                    ->orderBy('author_name')
                    ->orderBy('series')
                    ->orderBy('part')
                    ->orderBy('published_year')
                    ->get(),
            ]);
    }
}
