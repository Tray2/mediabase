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
    const REQUIRED = 'required';
    const SERIES = 'series';
    const AUTHORID = 'author_id';
    const BOOKS_INDEX = 'books.index';
    const BOOKID = 'book_id';

    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    protected function validateBook(Request $request, $validationRules = [])
    {
        $rules = array_merge([
            'title' => self::REQUIRED,
            self::SERIES => self::REQUIRED,
            'part' => [
                'required_unless:series,Standalone',
                'numeric',
                'nullable'
            ],
            'format_id' => 'required|exists:formats,id',
            'genre_id' => 'required|exists:genres,id',
            'isbn' => [
                self::REQUIRED,
                'regex:/^(97(8|9))?\d{9}(\d|X)$/'
            ],
            'released' => [self::REQUIRED, 'integer', 'between:1800,' . Carbon::now()->addYear(1)->year],
            'reprinted' => ['nullable', 'integer', 'between:1800,' . Carbon::now()->addYear(1)->year],
            'pages' => 'required|integer',
            'blurb' => self::REQUIRED
        ], $validationRules);

        return $request->validate($rules);
    }

    public function index()
    {
        $books = BookView::orderBy('author_name')
                         ->orderBy('series_started')
                         ->orderBy('part')
                         ->orderBy('released')
                         ->orderBy('title')
                         ->get();
        return view(self::BOOKS_INDEX)->with('books', $books);
    }

    public function show($id)
    {
        $book = Book::findOrFail($id);
        return view('books.show')->with('book', $book);
    }

    public function edit($id)
    {
        $book = Book::findOrFail($id);
        $additionalAuthors = Author::where('id', '!=', $book->author[0]->id)->get();
        $genres = Genre::orderBy('genre')->get();
        $formats = Format::orderBy('format')->get();
        return view('books.edit')->with(['book' => $book, 'genres' => $genres, 'formats' => $formats, 'additional_authors' => $additionalAuthors]);
    }

    public function create(Request $request)
    {
        if ($request->query(self::AUTHORID) == null) {
            return redirect('/authors')->with('error', 'You must specify an author.');
        }
        $author = Author::findOrFail($request->query(self::AUTHORID));
        $additionalAuthors = Author::where('id', '!=', $author->id)->get();
        $genres = Genre::where('type', 'book')->orderBy('genre')->get();
        $formats = Format::orderBy('format')->get();
        return view('books.create')->with(['genres' => $genres, 'formats' => $formats, 'author' => $author, 'additional_authors' => $additionalAuthors]);
    }

    public function update(Book $book, Request $request)
    {
        $this->validateBook($request, ['id' => 'required|exists:books,id']);

        $book->title = $request->title;
        $book->series = $request->series;
        $book->part = $request->part;
        $book->format_id = $request->format_id;
        $book->genre_id = $request->genre_id;
        $book->isbn = $request->isbn;
        $book->released = $request->released;
        $book->reprinted = $request->reprinted;
        $book->pages = $request->pages;
        $book->blurb = $request->blurb;

        $book->save();
        return redirect(route(self::BOOKS_INDEX))->withStatus($book->title . ' successfully updated.');
    }

    public function store(Request $request)
    {
        $this->setSeriesAttribute($request);
        $bookData = $this->validateBook($request);
        $book = Book::create($bookData);
        $this->AddAditionalAuthors($request, $book);
        $this->AddBookToUserCollection($book);
        $this->MarkBookAsRead($request, $book);

        return redirect(route(self::BOOKS_INDEX))->withStatus($book->title . ' successfully added.');
    }

    public function destroy(Book $book)
    {
        $book->delete();
        return redirect(route(self::BOOKS_INDEX))->withStatus($book->title . ' successfully deleted.');
    }

    /**
     * @param Request $request
     * @param $book
     */
    protected function AddAditionalAuthors(Request $request, $book): void
    {
        if (isset($request->additional_authors)) {
            $authors = array_merge([$request->author_id], $request->additional_authors);
        } else {
            $authors = [$request->author_id];
        }
        foreach ($authors as $author) {
            AuthorBook::create([
                'author_id' => $author,
                self::BOOKID => $book->id
            ]);
        }
    }

    /**
     * @param $book
     */
    protected function AddBookToUserCollection($book): void
    {
        BookCollection::create([
            self::BOOKID => $book->id,
            'user_id' => Auth::user()->id
        ]);
    }

    /**
     * @param Request $request
     * @param $book
     */
    protected function MarkBookAsRead(Request $request, $book): void
    {
        if (isset($request->read)) {
            BookRead::create([
                self::BOOKID => $book->id,
                'user_id' => Auth::user()->id
            ]);
        }
    }

    /**
     * @param Request $request
     */
    protected function setSeriesAttribute(Request $request): void
    {
        if (!$request->has(self::SERIES)) {
            $request->merge([self::SERIES => 'Standalone']);
        }
    }
}



