<?php

namespace App\Http\Requests;

use App\Models\Author;
use App\Models\Format;
use App\Models\Genre;
use App\Models\Publisher;
use App\Models\Series;
use App\Rules\Isbn;
use App\Rules\MinWords;
use App\Rules\RequiredIfNotStandalone;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class BookFormRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => 'required',
            'published_year' => ['required', 'numeric', 'min_digits:4', 'max_digits:4', 'between:1800,' . Carbon::now()->addYear(1)->year],
            'isbn' => ['required', new Isbn(),],
            'blurb' => ['required', new MinWords(3)],
            'author' => 'required',
            'genre_name' => 'required',
            'format_name' => 'required',
            'series_name' => 'required',
            'publisher_name' => 'required',
            'part' => [new RequiredIfNotStandalone($this->series_name),],

        ];
    }

    public function getSeriesId(): int
    {
        return Series::firstOrCreate(['name' => $this->series_name])
            ->value('id');
    }

    public function getFormatId(): int
    {
        return Format::firstOrCreate(['name' => $this->format_name])
            ->value('id');
    }

    public function getGenreId(): int
    {
        return Genre::FirstOrCreate(['name' => $this->genre_name])
            ->value('id');
    }

    public function getAuthor(): array
    {
        $authors = [];

        foreach($this->author as $author) {
            [$lastName, $firstName] = explode(', ', $author);
            $authors[] =  Author::firstOrCreate(
                ['last_name' => $lastName],
                ['first_name' => $firstName]
            )->value('id');
        }
        return $authors;
    }

    public function getPublisherId(): int
    {
        return Publisher::firstOrCreate(['name' => $this->publisher_name])
            ->value('id');
    }

}
