<?php

namespace App\Http\Controllers\Records;

use App\Http\Controllers\Controller;
use App\Http\Requests\RecordFormRequest;
use App\Models\Record;

class RecordsUpdateController extends Controller
{
    public function __invoke(Record $record, RecordFormRequest $request)
    {
        $valid = $request->validated();
        $record->update(array_merge($valid, [
            'genre_id' => $request->getGenreId(),
            'format_it' => $request->getFormatId(),
            'country_id' => $request->getCountryId(),
            'record_label_id' => $request->getRecordLabelId(),
            'artist_id' => $request->getArtistId(),
        ]));

        return redirect(route('records.index'));
    }
}
