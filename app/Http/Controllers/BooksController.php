<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookFormRequest;
use Illuminate\Http\Request;
use App\Book;
use App\Format;
use App\Genre;
use App\Author;
use App\BookView;

class BooksController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    public function index()
    {
        $books = BookView::orderBy('author_name')
                         ->orderBy('series_started')
                         ->orderBy('part')
                         ->orderBy('released')
                         ->orderBy('title')
                         ->get();
        return view('books.index')->with('books', $books);
    }

    public function show(Book $book)
    {
        return view('books.show')->with('book', $book);
    }

    public function create(Request $request)
    {
        if ($request->query('author_id') == null) {
            return redirect('/authors')->with('error', 'You must specify an author.');
        }
        $author = Author::findOrFail($request->query('author_id'));
        return view('books.create')->with([
            'genres' => Genre::where('media_type_id', env('BOOKS'))->orderBy('genre')->get(),
            'formats' => Format::where('media_type_id', env('BOOKS'))->orderBy('format')->get(),
            'author' => $author,
            'additional_authors' => Author::where('id', '!=', $author->id)->orderBy('last_name')->orderBy('first_name')->get()
        ]);
    }

    public function store(BookFormRequest $request)
    {
        $bookData = $request->validated();
        $book = Book::create($bookData);
        $book->addAuthors($request, $book);
        $book->addToCollection($book);
        $book->markAsRead($request, $book);

        return redirect(route('books.index'))->withStatus($book->title . ' successfully added.');
    }

    public function edit(Book $book)
    {
        return view('books.edit')->with(
            [
             'book' => $book,
             'genres' => Genre::orderBy('genre')->get(),
             'formats' => Format::orderBy('format')->get(),
             'additional_authors' => Author::where('id', '!=', $book->author[0]->id)->get()
            ]
        );
    }

    public function update(Book $book, BookFormRequest $request)
    {
        $book->update($request->validated());
        return redirect(route('books.index'))->withStatus($book->title . ' successfully updated.');
    }

    public function destroy(Book $book)
    {
        $book->delete();
        return redirect(route('books.index'))->withStatus($book->title . ' successfully deleted.');
    }
}
