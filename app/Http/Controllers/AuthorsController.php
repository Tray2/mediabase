<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthorFormRequest;
use App\Author;
use App\BookView;
use Illuminate\Support\Str;

class AuthorsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    public function index()
    {
        return view('authors.index')->with(['authors' => Author::orderBy('last_name', 'asc')
            ->orderBy('first_name', 'asc')
            ->withCount('books')
            ->get()]);
    }

    public function show($id)
    {
        if (is_numeric($id)) {
            $author = Author::findOrFail($id);
        } else {
            $author = Author::where('slug', $id)->firstOrFail();
        }

        return view('authors.show')->with(
            [
                'author' => $author,
                'books' => BookView::where('author_name', 'like', '%' . $author->name . '%')
                    ->get()
            ]
        );
    }

    public function create()
    {
        return view('authors.create');
    }

    public function store(AuthorFormRequest $request)
    {
        $authorData = $request->validated();
        $authorData['slug'] = Str::slug($authorData['last_name'] . ' ' . $authorData['first_name']);
        $author = Author::create($authorData);
        return redirect(route('authors.index'))->withStatus($author->name . ' successfully added.');
    }

    public function edit(Author $author)
    {
        return view('authors.edit')->with(['author' => $author]);
    }

    public function update(Author $author, AuthorFormRequest $request)
    {
        $authorData = $request->validated();
        $authorData['slug'] = Str::slug($authorData['last_name'] . ' ' .$authorData['first_name']);
        $author->update($authorData);
        return redirect(route('authors.index'))->withStatus($author->name . ' successfully updated.');
    }

    public function destroy(Author $author)
    {
        $author->delete();
        return redirect(route('authors.index'))->withStatus($author->name . ' successfully deleted.');
    }
}
