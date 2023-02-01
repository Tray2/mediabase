<?php

namespace App\Http\Controllers\Games;

use App\Http\Controllers\Controller;
use App\Models\GameFormatView;
use App\Models\GameGenreView;
use App\Models\GameShowView;
use App\Models\Platform;

class GamesEditController extends Controller
{
    public function __invoke(GameShowView $gameShowView)
    {
        return view('games.edit')
            ->with([
                'game' => $gameShowView,
                'genres' => GameGenreView::orderBy('name')->get(),
                'formats' => GameFormatView::orderBy('name')->get(),
                'platforms' => Platform::orderBy('name')->get(),
            ]);
    }
}
