<?php

namespace App\Http\Controllers;

use App\Models\Artist;
use App\Models\Format;
use App\Models\Genre;
use App\Models\RecordFormatView;
use App\Models\RecordLabel;

class RecordsCreateController extends Controller
{
    public function __invoke()
    {
        return view('records.create')
            ->with([
                'artists' => Artist::query()
                    ->orderBy('name')
                    ->get(),
                'formats' => RecordFormatView::query()
                    ->orderBy('name')
                    ->get(),
                'genres' => Genre::query()
                    ->orderBy('name')
                    ->get(),
                'recordLabels' => RecordLabel::query()
                    ->orderBy('name')
                    ->get(),
            ]);
    }
}
