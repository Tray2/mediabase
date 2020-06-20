<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Book;
use Carbon\Carbon;
use App\Format;
use App\Genre;
use App\Author;
use App\BookView;
use App\AuthorBook;
use App\BookCollection;
use App\BookRead;
use Illuminate\Support\Facades\Auth;

class BooksController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    protected function validation(Request $request, $validationRules = [])
    {
        $rules = array_merge([
            'title' => 'required',
            'series' => 'required',
            'part' => [
                'required_unless:series,Standalone',
                'numeric',
                'nullable'
            ],
            'format_id' => 'required|exists:formats,id',
            'genre_id' => 'required|exists:genres,id',
            'isbn' => [
                'required',
                'regex:/^(97(8|9))?\d{9}(\d|X)$/'
            ],
            'released' => ['required', 'integer', 'between:1800,' . Carbon::now()->addYear(1)->year],
            'reprinted' => ['nullable', 'integer', 'between:1800,' . Carbon::now()->addYear(1)->year],
            'pages' => 'required|integer',
            'blurb' => 'required'
        ], $validationRules);

        return $request->validate($rules);
    }

    /**
     * @param Request $request
     * @param $book
     */
    protected function addAditionalAuthors(Request $request, $book): void
    {
        if (isset($request->additional_authors)) {
            $authors = array_merge([$request->author_id], $request->additional_authors);
        } else {
            $authors = [$request->author_id];
        }
        foreach ($authors as $author) {
            AuthorBook::create([
                'author_id' => $author,
                'book_id' => $book->id
            ]);
        }
    }

    /**
     * @param $book
     */
    protected function addBookToUserCollection($book): void
    {
        BookCollection::create([
            'book_id' => $book->id,
            'user_id' => Auth::user()->id
        ]);
    }

    /**
     * @param Request $request
     * @param $book
     */
    protected function markBookAsRead(Request $request, $book): void
    {
        if (isset($request->read)) {
            BookRead::create([
                'book_id' => $book->id,
                'user_id' => Auth::user()->id
            ]);
        }
    }

    /**
     * @param Request $request
     */
    protected function setSeriesAttribute(Request $request): void
    {
        if (!$request->has('series')) {
            $request->merge(['series' => 'Standalone']);
        }
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

    public function create(Request $request)
    {
        if ($request->query('author_id') == null) {
            return redirect('/authors')->with('error', 'You must specify an author.');
        }
        $author = Author::findOrFail($request->query('author_id'));
        return view('books.create')->with([
            'genres' => Genre::where('type', 'books')->orderBy('genre')->get(),
            'formats' => Format::where('type', 'books')->orderBy('format')->get(),
            'author' => $author,
            'additional_authors' => Author::where('id', '!=', $author->id)->orderBy('last_name')->orderBy('first_name')->get()
        ]);
    }

    public function update(Book $book, Request $request)
    {
        $book->update($this->validation($request, ['id' => 'required|exists:books,id']));
        return redirect(route('books.index'))->withStatus($book->title . ' successfully updated.');
    }

    public function store(Request $request)
    {
        $this->setSeriesAttribute($request);
        $bookData = $this->validation($request);
        $book = Book::create($bookData);
        $this->addAditionalAuthors($request, $book);
        $this->addBookToUserCollection($book);
        $this->markBookAsRead($request, $book);

        return redirect(route('books.index'))->withStatus($book->title . ' successfully added.');
    }

    public function destroy(Book $book)
    {
        $book->delete();
        return redirect(route('books.index'))->withStatus($book->title . ' successfully deleted.');
    }

}



