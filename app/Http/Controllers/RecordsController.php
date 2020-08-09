<?php

namespace App\Http\Controllers;

use App\Artist;
use App\Format;
use App\Genre;
use App\Http\Requests\RecordFormRequest;
use App\Record;
use Illuminate\Http\Request;

class RecordsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
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
                'genres' => Genre::where('media_type_id', env('RECORDS'))->get(),
                'formats' => Format::where('media_type_id', env('RECORDS'))->get(),
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
