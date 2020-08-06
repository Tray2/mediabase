<?php

namespace App\Http\Controllers;

use App\Http\Requests\FormatFormRequest;
use App\Format;

class FormatsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    public function index()
    {
        return view('formats.index')
            ->with(['formats' => Format::orderBy('type')
                ->orderBy('format')
                ->withCount('books')
                ->get()]);
    }

    public function show($id)
    {
        return view('formats.show')->with(['format' => Format::findOrFail($id)]);
    }

    public function edit($id)
    {
        return view('formats.edit')->with(['format' => Format::findOrFail($id)]);
    }

    public function update(Format $format, FormatFormRequest $request)
    {
        $format->update($request->validated());
        return redirect(route('formats.index'))->withStatus($format->format . ' successfully updated.');
    }

    public function create()
    {
        return view('formats.create');
    }

    public function store(FormatFormRequest $request)
    {
        $format = Format::create($request->validated());
        return redirect(route('formats.index'))->withStatus($format->format . ' successfully added.');
    }

    public function destroy(Format $format)
    {
        $format->delete();
        return redirect(route('formats.index'))->withStatus($format->format . ' successfully deleted.');
    }
}
