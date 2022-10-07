<?php

namespace App\Http\Controllers;

use App\Models\Record;

class RecordsDeleteController extends Controller
{
    public function __invoke(Record $record)
    {
        $record->delete();
        return redirect(route('records.index'));
    }
}
