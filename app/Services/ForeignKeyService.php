<?php

namespace App\Services;

use App\Models\Artist;
use App\Models\Author;
use App\Models\Country;
use App\Models\Format;
use App\Models\Genre;
use App\Models\MediaType;
use App\Models\Publisher;
use App\Models\RecordLabel;
use App\Models\Series;

class ForeignKeyService
{
    public function getGenreId(string $genreName, string $mediaType): int
    {
        return Genre::firstOrCreate(
            ['name' => $genreName],
            ['media_type_id' => MediaType::query()
                ->where('name', $mediaType)
                ->value('id'),
            ])
            ->value('id');
    }

    public function getFormatId(string $formatName, string $mediaType): int
    {
        return Format::firstOrCreate(
            ['name' => $formatName],
            ['media_type_id' => MediaType::query()
                ->where('name', $mediaType)
                ->value('id'),
            ])
            ->value('id');
    }

    public function getSeriesId(string $seriesName): int
    {
        return Series::firstOrCreate([
            'name' => $seriesName
        ])->value('id');
    }

    public function getPublisherId(string $publisherName): int
    {
        return Publisher::firstOrCreate([
            'name' => $publisherName
        ])->value('id');
    }

    public function getAuthorIds(array $authors): array
    {
        $authorIds = [];

        foreach ($authors as $author) {
            [$lastName, $firstName] = explode(', ', $author);
            $authorIds[] = Author::firstOrCreate(
                ['last_name' => $lastName],
                ['first_name' => $firstName]
            )->value('id');
        }

        return $authorIds;
    }

    public function getArtistId(string $artist): int
    {
        return Artist::firstOrCreate([
            'name' => $artist
        ])->value('id');
    }

    public function getCountryId(string $countryName): int
    {
        return Country::firstOrCreate([
            'name' => $countryName
        ])->value('id');
    }

    public function getRecordLabelId(string $recordLabelName): int
    {
        return RecordLabel::firstOrCreate([
            'name' => $recordLabelName
        ])->value('id');
    }
}
