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
                    ->when($request['authors'], function ($query, $authors) {
                        $query->whereIn('author_id',
                            $this->numericStringToArray($authors));
                    })
                    ->when($request['published'], function ($query, $published) {
                        $query->where('published_year', $published);
                    })
                    ->when($request['genre'], function ($query, $genre) {
                        $query->where('genre', $genre);
                    })
                    ->when($request['format'], function ($query, $format) {
                        $query->where('format', $format);
                    })
                    ->when($request['search'], function ($query, $search) {
                        $query->where('title', 'LIKE',  "%$search%")
                        ->orWhere('author_name', 'LIKE', "%$search%")
                        ->orWhere('series', 'LIKE', "%$search%");
                    })
                    ->orderBy('author_name')
                    ->orderBy('series')
                    ->orderBy('part')
                    ->orderBy('published_year')
                    ->get(),
            ]);
    }

    protected function numericStringToArray($authors): array
    {
        return array_map('intval',
            explode(',',
                $authors));
    }
}
