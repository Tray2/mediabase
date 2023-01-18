<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\GameFormatView;
use App\Models\GameGenreView;

class GamesEditController extends Controller
{
    public function __invoke(Game $game)
    {
        return view('games.edit')
            ->with([
                'game' => $game,
                'genres' => GameGenreView::orderBy('name')->get(),
                'formats' => GameFormatView::orderBy('name')->get()
            ]);
    }
}
