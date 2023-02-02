<?php

namespace App\Http\Controllers\Records;

use App\Http\Controllers\Controller;
use App\Models\Artist;
use App\Models\Country;
use App\Models\RecordFormatView;
use App\Models\RecordGenreView;
use App\Models\RecordLabel;

class RecordsCreateController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

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
                'genres' => RecordGenreView::query()
                    ->orderBy('name')
                    ->get(),
                'recordLabels' => RecordLabel::query()
                    ->orderBy('name')
                    ->get(),
                'countries' => Country::query()
                    ->orderBy('name')
                    ->get(),
            ]);
    }
}
