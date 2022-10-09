<?php

namespace App\Http\Controllers\Records;

use App\Http\Controllers\Controller;
use App\Models\Record;

class RecordsDeleteController extends Controller
{
    public function __invoke(Record $record)
    {
        $record->delete();

        return redirect(route('records.index'));
    }
}
