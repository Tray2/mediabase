<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Genre;

class GenresController extends Controller
{
    const GENRE = 'genre';
    const GENRE_INDEX = 'genres.index';

    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    protected function validateGenre(Request $request, $validationRules = [])
    {
        $rules = array_merge([
            self::GENRE => 'required|unique:genres,genre',
            'type' => 'required'
        ], $validationRules);

        return $request->validate($rules);
    }

    public function index()
    {
        $genres = Genre::orderBy(self::GENRE)->get();
        return view(self::GENRE_INDEX, compact('genres'));
    }

    public function show($id)
    {
        $genre = Genre::findOrFail($id);
        return view('genres.show', compact(self::GENRE));
    }

    public function edit($id)
    {
        $genre = Genre::findOrFail($id);
        return view('genres.edit')->with([self::GENRE => $genre]);
    }

    public function create()
    {
        return view('genres.create');
    }

    public function store(Request $request)
    {
        $genre = Genre::create($this->validateGenre($request));
        return redirect(route(self::GENRE_INDEX))->withStatus($genre->genre . ' successfully added.');
    }

    public function update(Genre $genre, Request $request)
    {
        $this->validateGenre($request, ['id' => 'required|exists:genres']);
        $genre->genre = $request->genre;
        $genre->update();
        return redirect(route(self::GENRE_INDEX))->withStatus($genre->genre . ' successfully updated.');
    }

    public function destroy(Genre $genre)
    {
        $genre->delete();
        return redirect(route(self::GENRE_INDEX))->withStatus($genre->genre . ' successfully deleted.');
    }
}
