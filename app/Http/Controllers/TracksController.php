<?php

namespace App\Http\Controllers;

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

    public function store(Request $request)
    {
        $track = $request->validate([
            'track_no' => 'required',
            'title' => 'required',
            'mix' => 'required',
            'record_id' => 'required|exists:records,id'
        ]);
        Track::create($track);
    }

    public function update(Track $track, Request $request)
    {
        $request->validate([
            'track_no' => 'required',
            'title' => 'required',
            'mix' => 'required',
            'record_id' => 'required|exists:records,id',
            'id' => 'required|exists:tracks,id'
        ]);

        $track->track_no = $request->track_no;
        $track->title = $request->title;
        $track->mix = $request->mix;
        $track->save();
    }
}
