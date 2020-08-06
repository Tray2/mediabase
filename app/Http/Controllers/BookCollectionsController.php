<?php

namespace App\Http\Controllers;

use App\BookCollection;
use App\BookCollectionView;
use App\Book;
use App\Http\Requests\BookCollectionFormRequest;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class BookCollectionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index']);
    }

    public function index($id)
    {
        if (is_numeric($id)) {
            $user = User::findOrFail($id);
        } else {
            $user = User::where('slug', $id)->firstOrFail();
        }

        return view('book_collection.index')->with(['books' => BookCollectionView::whereUserId($user->id)
            ->orderBy('author_name')
            ->orderBy('series_started')
            ->orderBy('part')
            ->orderBy('released')
            ->orderBy('title')
            ->get(),
            'user' => $user]);
    }

    public function store(BookCollectionFormRequest $request)
    {
        $bookCollection = BookCollection::create($request->validated());
        return redirect('/books')->withStatus($bookCollection->book->title . ' successfully added to collection.');
    }

    public function destroy(Book $book)
    {
        BookCollection::whereUserId(Auth::user()->id)->where('book_id', $book->id)->delete();
        return redirect('/books')->withStatus($book->title . ' successfully removed from collection.');
    }
}
