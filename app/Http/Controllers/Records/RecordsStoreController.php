<?php

namespace App\Http\Controllers\Records;

use App\Http\Controllers\Controller;
use App\Http\Requests\RecordFormRequest;
use App\Models\Record;

class RecordsStoreController extends Controller
{
    public function __invoke(RecordFormRequest $request)
    {
        $valid = $request->validated();
        Record::create(array_merge($valid, [
            'genre_id' => $request->getGenreId(),
            'format_id' => $request->getFormatId(),
            'artist_id' => $request->getArtistId(),
            'country_id' => $request->getCountryId(),
            'record_label_id' => $request->getRecordLabelId(),
        ]));

        return redirect(route('records.index'));
    }
}
