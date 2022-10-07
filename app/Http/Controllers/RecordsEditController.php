<?php

namespace App\Http\Controllers;

use App\Models\Artist;
use App\Models\Country;
use App\Models\Format;
use App\Models\Genre;
use App\Models\RecordLabel;
use App\Models\RecordShowView;

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
                'formats' => Format::query()
                    ->orderBy('name')
                    ->get(),
                'genres' => Genre::query()
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
