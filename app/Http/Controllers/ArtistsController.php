<?php

namespace App\Http\Controllers;

use App\Models\Artist;
use App\Http\Requests\ArtistFormRequest;
use App\Http\Requests\ArtistRequest;
use App\Models\Record;
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
                'records' => Record::where('artist_id', $artist->id)
                                ->orderBy('released')
                                ->get()
            ]
        );
    }

    public function create()
    {
        return view('artists.create');
    }

    public function store(ArtistFormRequest $request)
    {
        $artistRecord = $request->validated();
        $artistRecord['slug'] = Str::slug($artistRecord['name']);
        $artist = Artist::create($artistRecord);
        return redirect(route('artists.index'))->withStatus($artist->name . ' successfully added.');
    }

    public function edit(Artist $artist)
    {
        return view('artists.edit')->with(['artist' => $artist]);
    }

    public function update(Artist $artist, ArtistFormRequest $request)
    {
        $validArtist = $request->validated();
        $validArtist['slug'] = Str::slug($validArtist['name']);
        $artist->update($validArtist);
        return redirect(route('artists.index'))->withStatus($artist->name . ' successfully updated.');
    }

    public function destroy(Artist $artist)
    {
        $artist->delete();
        return redirect(route('artists.index'))->withStatus($artist->name . ' successfully deleted.');
    }
}
