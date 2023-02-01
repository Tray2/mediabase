<?php

namespace App\Http\Controllers\Games;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\GameShowView;

class GamesShowController extends Controller
{
    public function __invoke(GameShowView $gameShowView)
    {
        return view('games.show')
            ->with([
                'game' => $gameShowView,
            ]);
    }
}
