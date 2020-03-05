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
        $formats = Format::orderBy('format')->get();
        return view('formats.index', compact('formats'));
    }

    public function show($id)
    {
        $format = Format::findOrFail($id);
        return view('formats.show', compact('format'));
    }

    public function edit($id)
    {
        $format = Format::findOrFail($id);
        return view('formats.edit', compact('format'));
    }

    public function update(Format $format, Request $request)
    {
        $this->validateFormat($request, ['id' => 'required|exists:formats,id']);

        $format->format = $request->format;
        $format->save();
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
