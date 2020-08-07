<?php

namespace App\Http\Controllers;

use App\Artist;
use App\Format;
use App\Genre;
use App\Http\Requests\RecordFormRequest;
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

    public function create(Request $request)
    {
        if ($request->query('artist_id') == null) {
            return redirect('/artists')->with('error', 'You must specify an artist.');
        }
        return view('records.create')
            ->with([
                'genres' => Genre::where('type', 'records')->get(),
                'formats' => Format::where('type', 'records')->get(),
                'artist' => Artist::findOrFail($request->query('artist_id'))
            ]
        );
    }

    public function store(RecordFormRequest $request)
    {
        Record::create($request->validated());
    }

    public function edit(Record $record)
    {
        return view('records.edit')->with(['record' => $record]);
    }

    public function update(Record $record, RecordFormRequest $request)
    {
        $record->update($request->validated());
    }

    public function destroy(Record $record)
    {
        $record->delete();
    }
}
