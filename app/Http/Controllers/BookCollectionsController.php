<?php

namespace App\Http\Controllers;

use App\BookCollection;
use App\BookCollectionView;
use App\Book;
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
        $user = '';
        if (is_numeric($id)) {
            $user = User::findOrFail($id);
        } else {
            $user = User::where('slug', $id)->firstOrFail();
        }

        $books = BookCollectionView::whereUserId($user->id)
        ->orderBy('author_name')
        ->orderBy('series_started')
        ->orderBy('part')
        ->orderBy('released')
        ->orderBy('title')
        ->get();

        return view('book_collection.index')->with(['books' => $books, 'user' => $user]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'exists:users,id',
            'book_id' => 'exists:books,id'
        ]);
        $bookCollection = new BookCollection();
        $bookCollection->book_id = $request->book_id;
        $bookCollection->user_id = $request->user_id;
        $bookCollection->save();
        return redirect('/books')->withStatus($bookCollection->book->title . ' successfully added to collection.');
    }

    public function destroy(Book $book)
    {
        BookCollection::whereUserId(Auth::user()->id)->where('book_id', $book->id)->delete();
        return redirect('/books')->withStatus($book->title . ' successfully removed from collection.');
    }
}
