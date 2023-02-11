<?php

namespace App\Http\Controllers\Games;

use App\Http\Controllers\Controller;
use App\Models\GameIndexView;
use Illuminate\Http\Request;

class GamesIndexController extends Controller
{
    public function __invoke(Request $request)
    {
        return view('games.index')
            ->with([
                'games' => GameIndexView::query()
                    ->when($request['search'], function ($query, $search) {
                        $query->where('title', 'LIKE',  "%$search%");
                    })
                    ->when($request['released'], function ($query, $released) {
                        $query->where('release_year', $released);
                    })
                    ->when($request['platform'], function($query, $platform){
                        $query->where('platform', $platform);
                    })
                    ->when($request['genre'], function($query, $genre) {
                        $query->where('genre', $genre);
                    })
                    ->when($request['format'], function($query, $format) {
                        $query->where('format', $format);
                    })
                    ->orderBy('title')
                    ->orderBy('release_year')
                    ->get()
            ]);
    }
}
