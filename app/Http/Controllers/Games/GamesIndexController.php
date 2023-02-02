<?php

namespace App\Http\Controllers\Games;

use App\Http\Controllers\Controller;
use App\Models\GameIndexView;

class GamesIndexController extends Controller
{
    public function __invoke()
    {
        return view('games.index')
            ->with([
                'games' => GameIndexView::query()
                    ->orderBy('title')
                    ->orderBy('release_year')
                    ->get(),
            ]);
    }
}
