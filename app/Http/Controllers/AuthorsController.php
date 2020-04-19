<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Author;
use App\BookView;
use Illuminate\Support\Str;

class AuthorsController extends Controller
{
    protected $messages = [
        'first_name.unique' => 'Author name not unique',
        'slug' => null
    ];

    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    protected function validateAuthor(Request $request, $validationRules = [])
    {
        $rules = array_merge([
            'first_name' => 'required|unique:authors,first_name,' . null . ',id,last_name,'. $request->last_name,
            'last_name' => 'required',
        ], $validationRules);

        return $request->validate($rules, $this->messages);
    }

    public function index()
    {
        $authors = Author::orderBy('last_name', 'asc')
            ->orderBy('first_name', 'asc')
            ->get();
        return view('authors.index')->with(['authors' => $authors]);
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

    public function edit(Author $author)
    {
        return view('authors.edit')->with(['author' => $author]);
    }

    public function update(Author $author, Request $request)
    {
        $authorData = $this->validateAuthor($request, ['id' => 'required|exists:authors,id']);
        $authorData['slug'] = Str::slug($authorData['last_name'] . ' ' .$authorData['first_name']);
        $author->update($authorData);
        return redirect(route('authors.index'))->withStatus($author->name . ' successfully updated.');
    }

    public function create()
    {
        return view('authors.create');
    }

    public function store(Request $request)
    {
        $authorData = $this->validateAuthor($request);
        $authorData['slug'] = Str::slug($authorData['last_name'] . ' ' . $authorData['first_name']);
        $author = Author::create($authorData);
        return redirect(route('authors.index'))->withStatus($author->name . ' successfully added.');
    }

    public function destroy(Author $author)
    {
        $author->delete();
        return redirect(route('authors.index'))->withStatus($author->name . ' successfully deleted.');
    }
}
