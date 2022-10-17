<?php

namespace App\Http\Controllers\Records;

use App\Http\Controllers\Controller;
use App\Models\Artist;
use App\Models\Country;
use App\Models\RecordFormatView;
use App\Models\RecordGenreView;
use App\Models\RecordLabel;
use App\Models\RecordShowView;
use App\Models\Track;

class RecordsEditController extends Controller
{
    public function __invoke(RecordShowView $recordShowView)
    {
        return view('records.edit')
            ->with([
                'record' => $recordShowView,
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
                'tracks' => Track::query()
                    ->where('record_id', $recordShowView->id)
                    ->orderBy('position')
                    ->get(),
            ]);
    }
}
