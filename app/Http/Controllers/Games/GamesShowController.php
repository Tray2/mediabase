<?php

namespace App\Http\Controllers\Games;

use App\Http\Controllers\Controller;
use App\Models\Game;

class GamesShowController extends Controller
{
    public function __invoke(Game $game)
    {
        return view('games.show')
            ->with([
                'game' => $game,
            ]);
    }
}
