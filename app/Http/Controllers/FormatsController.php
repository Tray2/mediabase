<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Format;

class FormatsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    protected function validateFormat(Request $request, $validationRules = [])
    {
        $rules = array_merge([
            'format' => 'required|unique:formats,format',
            'type' => 'required'
        ], $validationRules);

        return $request->validate($rules);
    }

    public function index()
    {
        return view('formats.index')->with(['formats' => Format::orderBy('format')->withCount('books')->get()]);
    }

    public function show($id)
    {
        return view('formats.show')->with(['format' => Format::findOrFail($id)]);
    }

    public function edit($id)
    {
        return view('formats.edit')->with(['format' => Format::findOrFail($id)]);
    }

    public function update(Format $format, Request $request)
    {
        $format->update($this->validateFormat($request, ['id' => 'required|exists:formats,id']));
        return redirect(route('formats.index'))->withStatus($format->format . ' successfully updated.');
    }

    public function create()
    {
        return view('formats.create');
    }

    public function store(Request $request)
    {
        $format = Format::create($this->validateFormat($request));
        return redirect(route('formats.index'))->withStatus($format->format . ' successfully added.');
    }

    public function destroy(Format $format)
    {
        $format->delete();
        return redirect(route('formats.index'))->withStatus($format->format . ' successfully deleted.');
    }
}
