<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookReadFormRequest;
use App\User;
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

    public function store(BookReadFormRequest $request)
    {
        $bookData = $request->validated();
        $bookData['user_id'] = $request->user()->id;
        $bookRead = BookRead::create($bookData);
        return redirect('/books/' . $bookRead->book_id)->withStatus($bookRead->book->title . ' marked as read.');
    }

    public function destroy(Book $book)
    {
        $bookRead = BookRead::whereBookId($book->id)->whereUserId(Auth::user()->id)->first();
        $bookRead->delete();
        return redirect('/books/' . $book->id)->withStatus($book->title . ' marked as unread.');
    }


}
