<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Genre;

class GenresController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    protected function validateGenre(Request $request, $validationRules = [])
    {
        $rules = array_merge([
            'genre' => 'required|unique:genres,genre',
            'type' => 'required'
        ], $validationRules);

        return $request->validate($rules);
    }

    public function index()
    {
        return view('genres.index')->with(['genres' => Genre::orderBy('genre')->get()]);
    }

    public function show($id)
    {
        return view('genres.show')->with(['genre' => Genre::findOrFail($id)]);
    }

    public function edit($id)
    {
        return view('genres.edit')->with(['genre' => Genre::findOrFail($id)]);
    }

    public function create()
    {
        return view('genres.create');
    }

    public function store(Request $request)
    {
        $genre = Genre::create($this->validateGenre($request));
        return redirect(route('genres.index'))->withStatus($genre->genre . ' successfully added.');
    }

    public function update(Genre $genre, Request $request)
    {
        $genre->update($this->validateGenre($request, ['id' => 'required|exists:genres']));
        return redirect(route('genres.index'))->withStatus($genre->genre . ' successfully updated.');
    }

    public function destroy(Genre $genre)
    {
        $genre->delete();
        return redirect(route('genres.index'))->withStatus($genre->genre . ' successfully deleted.');
    }
}
