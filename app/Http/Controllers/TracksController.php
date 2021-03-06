<?php

namespace App\Http\Controllers;

use App\Http\Requests\TrackFormRequest;
use App\Models\Track;

class TracksController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    public function create()
    {
        return view('tracks.create');
    }

    public function store(TrackFormRequest $request)
    {
        Track::create($request->validated());
    }

    public function edit(Track $track)
    {
        return view('tracks.edit')->with(['track' => $track]);
    }

    public function update(Track $track, TrackFormRequest $request)
    {
        $track->update($request->validate([
            'track_no' => 'required',
            'title' => 'required',
            'mix' => 'required',
            'record_id' => 'required|exists:records,id',
            'id' => 'required|exists:tracks,id'
        ]));
    }

    public function destroy(Track $track)
    {
        $track->delete();
        return redirect(route('records.show', $track->record->id))->withStatus($track->title . ' successfully deleted.');
    }
}
