<?php

namespace App\Services;

use App\Models\Track;
use Illuminate\Support\Str;

class TracksService
{
    public function storeTracks(array $tracks, ForeignKeyService $foreignKeyService): void
    {
        for($i = 0; $i < $tracks['track_count']; $i++)
        {
            $track = [
                'position' => Str::padLeft($tracks['track_positions'][$i], 2, '0'),
                'title' => $tracks['track_titles'][$i],
                'duration' => $tracks['track_durations'][$i],
                'mix' => $tracks['track_mixes'][$i] ?? null,
                'record_id' => $tracks['record_id']
            ];
            if ($this->isVariousArtists($tracks['record_artist'])) {
                $track['artist_id'] = $foreignKeyService->getArtistId($tracks['track_artists'][$i]);
            }
            Track::create($track);
        }
    }

    protected function isVariousArtists(string $artist): bool
    {
        return $artist === 'Various Artists';
    }

    public function updateTracks(array $tracksArray, ForeignKeyService $foreignKeyService): void
    {
        $tracks = Track::query()
            ->where('record_id', $tracksArray['record_id'])
            ->get();

        $i = 0;

        foreach ($tracks as $track) {
            $track->position = Str::padLeft($tracksArray['track_positions'][$i], 2, '0');
            $track->title = $tracksArray['track_titles'][$i];
            $track->duration = $tracksArray['track_durations'][$i];
            $track->mix = $tracksArray['track_mixes'][$i];
            if ($this->isVariousArtists($tracksArray['record_artist'])) {
                $track->artist_id = $foreignKeyService->getArtistId($tracksArray['track_artists'][$i]);
            }
            $track->save();
            $i++;
        }

    }
}
