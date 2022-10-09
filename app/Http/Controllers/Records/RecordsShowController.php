<?php

namespace App\Http\Controllers\Records;

use App\Http\Controllers\Controller;
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
