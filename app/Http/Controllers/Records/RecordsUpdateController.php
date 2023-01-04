<?php

namespace App\Http\Controllers\Records;

use App\Http\Controllers\Controller;
use App\Http\Requests\RecordFormRequest;
use App\Models\Record;
use App\Services\ForeignKeyService;
use App\Services\TracksService;

class RecordsUpdateController extends Controller
{
    public function __invoke(Record $record, RecordFormRequest $request, TracksService $tracksService, ForeignKeyService $foreignKeyService)
    {
        $valid = $request->validated();
        $record->update(array_merge($valid, [
            'genre_id' => $foreignKeyService->getGenreId($request->genre_name, 'record'),
            'format_it' => $foreignKeyService->getFormatId($request->format_name, 'record'),
            'country_id' => $foreignKeyService->getCountryId($request->country_name),
            'record_label_id' => $foreignKeyService->getRecordLabelId($request->record_label_name),
            'artist_id' => $foreignKeyService->getArtistId($request->artist),
        ]));

        $tracksService->updateTracks([
            'track_positions' => $valid['track_positions'],
            'track_titles' => $valid['track_titles'],
            'track_durations' => $valid['track_durations'],
            'track_mixes' => $valid['track_mixes'] ?? null,
            'track_artists' => $valid['track_artists'] ?? null,
            'record_id' => $record->id,
            'record_artist' => $request->artist,
        ], $foreignKeyService);

        return redirect(route('records.index'));
    }
}
