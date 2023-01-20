<?php

namespace App\Http\Controllers\Games;

use App\Http\Controllers\Controller;
use App\Models\Game;

class GamesIndexController extends Controller
{
    public function __invoke()
    {
        return view('games.index')
            ->with([
                'games' => Game::query()
                    ->orderBy('title')
                    ->orderBy('release_year')
                    ->get()
            ]);
    }
}
