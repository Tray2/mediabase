<?php

namespace App\Http\Controllers\Records;

use App\Http\Controllers\Controller;
use App\Http\Requests\RecordFormRequest;
use App\Models\Record;
use App\Models\Track;
use Illuminate\Support\Str;

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

        $i = 0;

        foreach ($tracks as $track) {
            $track->position = Str::padLeft($valid['track_positions'][$i], 2, '0');
            $track->title = $valid['track_titles'][$i];
            $track->duration = $valid['track_durations'][$i];
            $track->mix = $valid['track_mixes'][$i];
            if ($request->isVariousArtists()) {
                $track->artist_id = $request->getTrackArtistId($valid['track_artists'][$i]);
            }
            $track->save();
            $i++;
        }
        return redirect(route('records.index'));
    }
}
