<?php

namespace App\Http\Controllers;

use App\Genre;
use App\Record;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RecordsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    public function index()
    {
        $records = Record::all();
        return view('records.index')->with(['records' => $records]);
    }

    public function show(Record $record)
    {
        return view('records.show')->with(['record' => $record]);
    }

    public function create()
    {
        $genres = Genre::where('type', 'record')->get();
        return view('records.create')->with(['genres' => $genres]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'artist_id' => 'required|exists:artists,id',
            'title' => 'required',
            'released' => ['required', 'integer', 'between:1800,' . Carbon::now()->addYear(1)->year],
            'genre_id' => 'required|exists:genres,id',
            'format_id' => 'required|exists:formats,id',
            'release_code' => 'required',
            'barcode' => 'sometimes'
        ]);
        Record::create($request->all());
    }

    public function edit(Record $record)
    {
        return view('records.edit')->with(['record' => $record]);
    }

    public function update(Record $record, Request $request)
    {
        $request->validate([
            'artist_id' => 'required|exists:artists,id',
            'title' => 'required',
            'released' => ['required', 'integer', 'between:1800,' . Carbon::now()->addYear(1)->year],
            'genre_id' => 'required|exists:genres,id',
            'format_id' => 'required|exists:formats,id',
            'release_code' => 'required',
            'barcode' => 'sometimes',
            'id' => 'required|exists:records,id'
        ]);
        $record->artist_id = $request->artist_id;
        $record->title = $request->title;
        $record->released = $request->released;
        $record->genre_id = $request->genre_id;
        $record->format_id = $request->format_id;
        $record->release_code = $request->release_code;
        $record->barcode = $request->barcode;
        $record->save();
    }

    public function destroy(Record $record)
    {
        $record->delete();
    }
}
