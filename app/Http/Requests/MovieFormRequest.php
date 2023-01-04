<?php

namespace App\Http\Requests;

use App\Models\Actor;
use App\Models\Format;
use App\Models\Genre;
use App\Models\MediaType;
use App\Rules\MinWords;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class MovieFormRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => 'required',
            'release_year' => ['required', 'numeric', 'min_digits:4', 'max_digits:4', 'between:1800,'.Carbon::now()->addYear(1)->year],
            'blurb' => ['required', new MinWords(3)],
            'runtime' => ['required', 'numeric', 'min_digits:2', 'max_digits:3', 'between:1,999'],
            'actor' => 'required',
            'genre_name' => 'required',
            'format_name' => 'required',
        ];
    }

    public function getFormatId(): int
    {
        return Format::firstOrCreate(
            ['name' => $this->format_name],
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

    public function getActor(): array
    {
        $actors = [];
        foreach ($this->actor as $actor) {
            [$firstName, $lastName] = explode(' ', $actor);
            $actors[] = Actor::firstOrCreate(
                ['last_name' => $lastName],
                ['first_name' => $firstName]
            )->value('id');
        }

        return $actors;
    }

    protected function getMediaType(): string
    {
        return Str::singular(explode('/', trim($this->getPathInfo(), '/'))[0]);
    }
}
