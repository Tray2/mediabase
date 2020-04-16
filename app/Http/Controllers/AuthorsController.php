<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Author;
use App\BookView;
use Illuminate\Support\Str;

class AuthorsController extends Controller
{
    const AUTHOR_INDEX = 'authors.index';

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
        return view(self::AUTHOR_INDEX)->with(['authors' => $authors]);
    }

    public function show($id)
    {
        $author = '';
        if (is_numeric($id)) {
            $author = Author::findOrFail($id);
        } else {
            $author = Author::where('slug', $id)->firstOrFail();
        }

        $books = BookView::where('author_name', 'like', '%' . $author->name . '%')->get();
        return view('authors.show')->with(['author' => $author, 'books' => $books]);
    }

    public function edit($id)
    {
        $author = Author::findOrFail($id);
        return view('authors.edit')->with(['author' => $author]);
    }

    public function update(Author $author, Request $request)
    {
        $this->validateAuthor($request, ['id' => 'required|exists:authors,id']);
        $author->first_name = $request->first_name;
        $author->last_name= $request->last_name;
        $author->update();
        return redirect(route(self::AUTHOR_INDEX))->withStatus($author->name . ' successfully updated.');
    }

    public function create()
    {
        return view('authors.create');
    }

    public function store(Request $request)
    {
        $validAuthor = $this->validateAuthor($request);
        $validAuthor['slug'] = Str::slug($validAuthor['last_name'] . ' ' . $validAuthor['first_name']);
        $author = Author::create($validAuthor);
        return redirect(route(self::AUTHOR_INDEX))->withStatus($author->name . ' successfully added.');
    }

    public function destroy(Author $author)
    {
        $author->delete();
        return redirect(route(self::AUTHOR_INDEX))->withStatus($author->name . ' successfully deleted.');
    }
}
