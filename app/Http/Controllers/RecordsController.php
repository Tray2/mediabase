<?php

namespace App\Http\Controllers;

use App\Models\Artist;
use App\Models\Format;
use App\Models\Genre;
use App\Http\Requests\RecordFormRequest;
use App\Models\Record;
use App\Models\RecordView;
use Illuminate\Http\Request;

class RecordsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    public function index()
    {
        return view('records.index')->with(['records' => RecordView::all()]);
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
                'genres' => Genre::where('media_type_id', env('RECORDS'))->orderBy('genre')->get(),
                'formats' => Format::where('media_type_id', env('RECORDS'))->orderBy('format')->get(),
                'artist' => Artist::findOrFail($request->query('artist_id'))
            ]
        );
    }

    public function store(RecordFormRequest $request)
    {
        $record = Record::create($request->validated());
        return redirect(route('records.index'))->withStatus($record->title . ' successfully added.');
    }

    public function edit(Record $record)
    {
        return view('records.edit')->with([
            'record' => $record,
            'genres' => Genre::where('media_type_id', env('RECORDS'))->orderBy('genre')->get(),
            'formats' => Format::where('media_type_id', env('RECORDS'))->orderBy('format')->get()]);
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
