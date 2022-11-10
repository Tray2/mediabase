<?php

namespace App\Http\Controllers\Records;

use App\Http\Controllers\Controller;
use App\Http\Requests\RecordFormRequest;
use App\Models\Record;
use App\Models\Track;

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

        $tracks = Track::query()
            ->where('record_id', $record->id)
            ->get();

        foreach ($tracks as $track) {
            $i = 0;
            $track->position = $valid['track_positions'][$i];
            $track->title = $valid['track_titles'][$i];
            $track->duration = $valid['track_durations'][$i];
            $track->mix = $valid['track_mixes'][$i];
            $track->save();
            $i++;
        }
        return redirect(route('records.index'));
    }
}
