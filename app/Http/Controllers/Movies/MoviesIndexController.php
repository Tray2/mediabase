<?php

namespace App\Http\Controllers\Movies;

use App\Http\Controllers\Controller;
use App\Models\MovieIndexView;
use Illuminate\Http\Request;

class MoviesIndexController extends Controller
{
    public function __invoke(Request $request)
    {
        return view('movies.index')
            ->with([
                'movies' => MovieIndexView::query()
                    ->when($request['search'], function ($query, $search) {
                        $query->where('title', 'LIKE',  "%$search%");
                    })
                    ->when($request['released'], function ($query, $released) {
                        $query->where('release_year', $released);
                    })
                    ->when($request['genre'], function($query, $genre) {
                        $query->where('genre', $genre);
                    })
                    ->when($request['format'], function($query, $format) {
                        $query->where('format', $format);
                    })
                    ->orderBy('title')
                    ->orderBy('release_year')
                    ->get(),
            ]);
    }
}
