<?php

namespace App\Http\Controllers;

use App\Http\Requests\GenreFormRequest;
use App\Models\Genre;
use App\Models\MediaType;
use Illuminate\Http\Request;

class GenresController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    public function index(Request $request)
    {
        if ($request->type) {
            $genres = Genre::where('media_type_id', env($request->type))
                ->orderBy('genre')
                ->withCount(strtolower($request->type))
                ->get();
        } else {
            $genres = Genre::orderBy('genre')->withCount('books')->get();
        }
        return view('genres.index')->with([
            'genres' => $genres,
            'type' => $request->type
        ]);
    }

    public function show($id, Request $request)
    {
        $genre = '';
        if ($request->type == 'BOOKS') {
            $genre = Genre::with(['books' => function ($query){
                $query->orderBy('author_name')
                    ->orderBy('series_started')
                    ->orderBy('part')
                    ->orderBy('released')
                    ->orderBy('title');
            }])->findOrFail($id);
        } elseif ($request->type == 'RECORDS') {
            $genre = Genre::with(['records' => function ($query){
                $query->orderBy('name')
                    ->orderBy('released')
                    ->orderBy('title');
            }])->findOrFail($id);
        }
        return view('genres.show')->with([
            'genre' => $genre,
            'type' => $request->type
        ]);
    }

    public function create(Request $request)
    {
        return view('genres.create')->with(['mediaTypes' => MediaType::all(), 'type' => $request->type]);
    }

    public function store(GenreFormRequest $request)
    {
        $genre = Genre::create($request->validated());
        return redirect(route('genres.index'))->withStatus($genre->genre . ' successfully added.');
    }
    public function edit($id)
    {
        return view('genres.edit')->with([
            'genre' => Genre::findOrFail($id),
            'mediaTypes' => MediaType::all()
        ]);
    }

    public function update(Genre $genre, GenreFormRequest $request)
    {
        $genre->update($request->validated());
        return redirect(route('genres.index'))->withStatus($genre->genre . ' successfully updated.');
    }

    public function destroy(Genre $genre)
    {
        $genre->delete();
        return redirect(route('genres.index'))->withStatus($genre->genre . ' successfully deleted.');
    }
}
