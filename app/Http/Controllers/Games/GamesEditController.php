<?php

namespace App\Http\Controllers\Games;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\GameFormatView;
use App\Models\GameGenreView;
use App\Models\Platform;

class GamesEditController extends Controller
{
    public function __invoke(Game $game)
    {
        return view('games.edit')
            ->with([
                'game' => $game,
                'genres' => GameGenreView::orderBy('name')->get(),
                'formats' => GameFormatView::orderBy('name')->get(),
                'platforms' => Platform::orderBy('name')->get(),
            ]);
    }
}
