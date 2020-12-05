<?php

namespace App\Http\Controllers;

use App\Http\Requests\FormatFormRequest;
use App\Models\Format;
use App\Models\MediaType;
use Illuminate\Http\Request;

class FormatsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    public function index(Request $request)
    {
        if(isset($request->type)) {
            return view('formats.index')->with(['formats' => Format::where('media_type_id', env(strtoupper($request->type)))
                ->orderBy('format')
                ->withCount(strtolower($request->type))
                ->get()]);
        }

        return view('formats.index')->with(['formats' => Format::orderBy('media_type_id')
            ->orderBy('format')
            ->get()]);
    }

    public function show($id)
    {
        return view('formats.show')->with(['format' => Format::findOrFail($id)]);
    }

    public function create()
    {
        return view('formats.create')->with(['mediaTypes' => MediaType::all()]);
    }

    public function store(FormatFormRequest $request)
    {
        $format = Format::create($request->validated());
        return redirect(route('formats.index'))->withStatus($format->format . ' successfully added.');
    }

    public function edit($id)
    {
        return view('formats.edit')->with([
            'format' => Format::findOrFail($id),
            'mediaTypes' => MediaType::all()
        ]);
    }

    public function update(Format $format, FormatFormRequest $request)
    {
        $format->update($request->validated());
        return redirect(route('formats.index'))->withStatus($format->format . ' successfully updated.');
    }

    public function destroy(Format $format)
    {
        $format->delete();
        return redirect(route('formats.index'))->withStatus($format->format . ' successfully deleted.');
    }
}
