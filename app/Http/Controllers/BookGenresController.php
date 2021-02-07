<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use Illuminate\Http\Request;

class BookGenresController extends Controller
{
    public function index()
    {
        return view('books.genres.index')->with([
            'genres' => Genre::where('media_type_id', env('BOOKS'))
                ->orderBy('genre')
                ->withCount('books')
                ->get(),
            'type' => 'books'
        ]);
    }
}
