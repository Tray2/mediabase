<?php

namespace App\Http\Controllers;

use App\Artist;
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
        $artists = Artist::all();
        return view('artists.index')->with(['artists' => $artists]);
    }

    public function show($id)
    {
        $artist = '';
        if(is_numeric($id)) {
            $artist = Artist::findOrFail($id);
        } else {
            $artist = Artist::where('slug', $id)->firstOrFail();
        }
        $records = Record::where('artist_id', $artist->id)->get();
        return view('artists.show')->with(['artist' => $artist, 'records' => $records]);
    }

    public function create()
    {
        return view('artists.create');
    }

    public function store(Request $request)
    {
        $artist = $request->validate([
            'name' => 'required|unique:artists,name',
            'slug' => 'nullable'
        ]);
        $artist['slug'] = Str::slug($artist['name']);
        Artist::create($artist);
    }

    public function edit(Artist $artist)
    {
        return view('artists.edit')->with(['artist' => $artist]);
    }

    public function update(Artist $artist, Request $request)
    {
        $request->validate(['name' => 'required|unique:artists,name', 'id' => 'required|exists:artists,id' ]);
        $artist->name = $request->name;
        $artist->save();
    }

    public function destroy(Artist $artist)
    {
        $artist->delete();
    }
}
