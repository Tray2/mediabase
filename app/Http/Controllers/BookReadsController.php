<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\BookView;
use App\BookRead;
use App\Book;
use Illuminate\Support\Facades\Auth;

class BookReadsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    public function index(User $user)
    {
        return view('book_read.index')
            ->with(
                [
                    'user' => $user,
                    'books' => BookView::whereIn('book_id', BookRead::whereUserId($user->id)->pluck('id'))->get()
                ]
            );
    }

    public function store(Request $request)
    {
        BookRead::create(['book_id' => $request->book_id, 'user_id' => Auth::user()->id]);
        $book = Book::findOrFail($request->book_id);
        return redirect('/books/' . $book->id)->withStatus($book->title . ' marked as read.');
    }

    public function destroy(Book $book)
    {
        $bookRead = BookRead::whereBookId($book->id)->whereUserId(Auth::user()->id)->first();
        $bookRead->delete();
        return redirect('/books/' . $book->id)->withStatus($book->title . ' marked as unread.');
    }


}
