<?php

namespace App\Http\Controllers\Records;

use App\Http\Controllers\Controller;
use App\Http\Requests\RecordFormRequest;
use App\Models\Record;
use App\Models\Track;
use Illuminate\Support\Str;

class RecordsStoreController extends Controller
{
    public function __invoke(RecordFormRequest $request)
    {
        $valid = $request->validated();
        $record = Record::create(array_merge($valid, [
            'genre_id' => $request->getGenreId(),
            'format_id' => $request->getFormatId(),
            'artist_id' => $request->getArtistId(),
            'country_id' => $request->getCountryId(),
            'record_label_id' => $request->getRecordLabelId(),
        ]));

        $tracks = count($valid['track_positions']);

        for($i = 0; $i < $tracks; $i++)
        {
          $track = [
              'position' => Str::padLeft($valid['track_positions'][$i], 2, '0'),
              'title' => $valid['track_titles'][$i],
              'duration' => $valid['track_durations'][$i],
              'mix' => $valid['track_mixes'][$i] ?? null,
              'record_id' => $record->id,
          ];
          if ($request->isVariousArtists()) {
              $track['artist_id'] = $request->getTrackArtistId($valid['track_artists'][$i]);
          }
          Track::create($track);
        }
        return redirect(route('records.index'));
    }
}
