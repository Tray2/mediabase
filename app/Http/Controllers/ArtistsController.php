<?php

namespace App\Http\Controllers;

use App\Artist;
use App\Http\Requests\ArtistRequest;
use App\Record;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ArtistsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    public function index()
    {
        return view('artists.index')->with(['artists' => Artist::orderBy('name')->get()]);
    }

    public function show($id)
    {
        if(is_numeric($id)) {
            $artist = Artist::findOrFail($id);
        } else {
            $artist = Artist::where('slug', $id)->firstOrFail();
        }

        return view('artists.show')->with(
            [
                'artist' => $artist,
                'records' => Record::where('artist_id', $artist->id)->get()
            ]
        );
    }

    public function create()
    {
        return view('artists.create');
    }

    public function store(Request $request)
    {
        $artistRecord = $request->validate([
            'name' => 'required|unique:artists,name',
            'slug' => 'nullable'
        ]);
        $artistRecord['slug'] = Str::slug($artistRecord['name']);
        $artist = Artist::create($artistRecord);
        return redirect(route('artists.index'))->withStatus($artist->name . ' successfully added.');
    }

    public function edit(Artist $artist)
    {
        return view('artists.edit')->with(['artist' => $artist]);
    }

    public function update(Artist $artist, Request $request)
    {
        $artistRecord = $request->validate([
                'name' => 'required|unique:artists,name',
                'id' => 'required|exists:artists,id'
            ]
        );
        $artistRecord['slug'] = Str::slug($artistRecord['name']);
        $artist->update($artistRecord);
        return redirect(route('artists.index'))->withStatus($artist->name . ' successfully updated.');
    }

    public function destroy(Artist $artist)
    {
        $artist->delete();
        return redirect(route('artists.index'))->withStatus($artist->name . ' successfully deleted.');
    }
}
