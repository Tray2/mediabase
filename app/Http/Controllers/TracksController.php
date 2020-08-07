<?php

namespace App\Http\Controllers;

use App\Http\Requests\TrackFormRequest;
use App\Track;
use Illuminate\Http\Request;

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

    public function edit(Track $track)
    {
        return view('tracks.edit')->with(['track' => $track]);
    }

    public function store(TrackFormRequest $request)
    {
        Track::create($request->validated());
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
}
