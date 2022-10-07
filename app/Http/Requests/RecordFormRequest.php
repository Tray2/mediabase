<?php

namespace App\Http\Requests;

use App\Models\Artist;
use App\Models\Country;
use App\Models\Format;
use App\Models\Genre;
use App\Models\MediaType;
use App\Models\RecordLabel;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class RecordFormRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'artist' => 'required',
            'title' => 'required',
            'barcode' => 'required',
            'spine_code' => 'required',
            'release_year' => ['required', 'numeric', 'min_digits:4', 'max_digits:4', 'between:1800,' . Carbon::now()->addYear(1)->year],
            'format_name' => 'required',
            'genre_name' => 'required',
            'country_name' => 'required',
            'record_label_name' => 'required',
        ];
    }

    public function getArtistId(): int
    {
        return Artist::firstOrCreate(
            ['name' => $this->artist,]
        )->value('id');
    }

    public function getCountryId(): int
    {
        return Country::firstOrCreate(
            ['name' => $this->country_name]
        )->value('id');
    }

    public function getFormatId(): int
    {
        return Format::firstOrCreate(
            ['name' => $this->format_name,],
            ['media_type_id' => MediaType::query()
                ->where('name', $this->getMediaType())
                ->value('id'),
            ])
            ->value('id');
    }

    public function getGenreId(): int
    {
        return Genre::FirstOrCreate(
            ['name' => $this->genre_name],
            ['media_type_id' => MediaType::query()
                ->where('name', $this->getMediaType())
                ->value('id'),
            ])
            ->value('id');
    }

    public function getRecordLabelId(): int
    {
        return RecordLabel::firstOrCreate(['name' => $this->record_label_name])
            ->value('id');
    }

    protected function getMediaType(): string
    {
        return Str::singular(explode('/',trim($this->getPathInfo(), '/'))[0]);
    }
}
