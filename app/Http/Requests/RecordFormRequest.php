<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class RecordFormRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'artist' => ['required', 'string'],
            'title' => ['required', 'string'],
            'barcode' => ['required', 'string'],
            'spine_code' => ['required', 'string'],
            'release_year' => ['required', 'numeric', 'min_digits:4', 'max_digits:4', 'between:1800,'.Carbon::now()->addYear(1)->year],
            'format_name' => ['required', 'string'],
            'genre_name' => ['required', 'string'],
            'country_name' => ['required', 'string'],
            'record_label_name' => ['required', 'string'],
            'track_positions' => 'required|array',
            'track_positions.*' => 'required|numeric|min:1|max_digits:2',
            'track_titles' => 'required|array',
            'track_titles.*' => 'required',
            'track_durations' => 'required|array',
            'track_durations.*' => 'required|date_format:i:s',
            'track_mixes' => 'sometimes|required|array',
            'track_mixes.*' => 'sometimes',
            'track_artists' => 'required_if:artist,Various Artists|array',
            'track_artists.*' => 'required_if:artist,Various Artists',
        ];
    }
}
