<?php

namespace App\Http\Controllers;

use App\Models\RecordIndexView;

class RecordsIndexController extends Controller
{
    public function __invoke()
    {
        return view('records.index')
            ->with([
               'records' => RecordIndexView::query()
                    ->orderBy('artist')
                    ->orderBy('release_year')
                    ->get(),
            ]);
    }
}
