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

    protected function validateRecord(Request $request, $validationRules = [])
    {
        $rules = array_merge([
            'artist_id' => 'required|exists:artists,id',
            'title' => 'required',
            'released' => ['required', 'integer', 'between:1800,' . Carbon::now()->addYear(1)->year],
            'genre_id' => 'required|exists:genres,id',
            'format_id' => 'required|exists:formats,id',
            'release_code' => 'required',
            'barcode' => 'sometimes'
        ], $validationRules);

        return $request->validate($rules);
    }


    public function index()
    {
        return view('records.index')->with(['records' => Record::all()]);
    }

    public function show(Record $record)
    {
        return view('records.show')->with(['record' => $record]);
    }

    public function create()
    {
        return view('records.create')->with(['genres' => Genre::where('type', 'record')->get()]);
    }

    public function store(Request $request)
    {
        Record::create($this->validateRecord($request));
    }

    public function edit(Record $record)
    {
        return view('records.edit')->with(['record' => $record]);
    }

    public function update(Record $record, Request $request)
    {
        $record->update($this->validateRecord($request,['id' => 'required|exists:records,id']));
    }

    public function destroy(Record $record)
    {
        $record->delete();
    }
}
