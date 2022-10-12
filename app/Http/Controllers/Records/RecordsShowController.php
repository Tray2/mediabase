<?php

namespace App\Http\Controllers\Records;

use App\Http\Controllers\Controller;
use App\Models\RecordIndexView;
use App\Models\RecordShowView;

class RecordsShowController extends Controller
{
    public function __invoke(RecordShowView $recordShowView)
    {
        return view('records.show')
            ->with([
                'record' => $recordShowView,
                'otherRecords' => RecordIndexView::query()
                    ->where('artist_id', $recordShowView->artist_id)
                    ->whereNot('record_id', $recordShowView->id)
                    ->orderBy('release_year')
                    ->get()
            ]);
    }
}
