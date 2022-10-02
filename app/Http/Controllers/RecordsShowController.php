<?php

namespace App\Http\Controllers;

use App\Models\RecordShowView;

class RecordsShowController extends Controller
{
    public function __invoke(RecordShowView $recordShowView)
    {
        return view('records.show')
            ->with([
                'record' => $recordShowView,
            ]);
    }
}
