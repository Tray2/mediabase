<?php

namespace App\Http\Controllers\Records;

use App\Http\Controllers\Controller;
use App\Models\RecordIndexView;
use Illuminate\Http\Request;

class RecordsIndexController extends Controller
{
    public function __invoke(Request $request)
    {
        return view('records.index')
            ->with([
                'records' => RecordIndexView::query()
                    ->when($request['search'], function ($query, $search) {
                        $query->where('title', 'LIKE',  "%$search%");
                    })
                    ->when($request['artist'], function ($query, $artist) {
                        $query->where('artist_id', $artist);
                    })
                    ->when($request['released'], function ($query, $released) {
                        $query->where('release_year', $released);
                    })
                    ->when($request['genre'], function ($query, $genre) {
                        $query->where('genre_name', $genre);
                    })
                    ->when($request['format'], function ($query, $format) {
                        $query->where('format_name', $format);
                    })
                    ->orderBy('artist')
                    ->orderBy('release_year')
                    ->get(),
            ]);
    }
}
