<?php

namespace Tests\Unit\Validation;

use App\Models\Artist;
use App\Models\Format;
use App\Models\Genre;
use App\Models\Record;
use Carbon\Carbon;
use Tests\TestCase;

class RecordValidationTest extends TestCase
{
    private $format;
    private $genre;
    private $artist;

    public function setUp(): void
    {
        parent::setUp();
        $this->format = Format::factory()->create([
            'format' => 'CD'
        ]);
        $this->genre = Genre::factory()->create([
            'genre' => 'Rap',
            'media_type_id' => env('RECORDS')
        ]);

        $this->artist = Artist::factory()->create([
            'name' => 'Run Dmc'
        ]);
    }

    /**
     * @test
     * @dataProvider validRecordProvider
     * @param $field
     * @param $fieldValue
     */
    public function a_valid_record_can_be_stored($field, $fieldValue)
    {
        $this->signIn();
        $record = Record::factory()->make([
            'artist_id' => $this->artist->id,
            'title' => 'King Of Rock',
            'released' => '1986',
            'genre_id' => $this->genre->id,
            'format_id' => $this->format->id,
            'release_code' => 'PRO112',
            'barcode' => $fieldValue
        ]);

        $this->post('/records', $record->toArray());
        $this->assertDatabaseHas('records',[
            'title' => $record->title
        ]);
    }

    public function validRecordProvider()
    {
        return [
          'can store barcode' => ['barcode', 12555458664],
          'can store without barcode' => ['barcode', '']
        ];
    }

    /**
     * @test
     * @dataProvider recordValidationProvider
     * @param $formInput
     * @param $formInputValue
     */
    public function store_validation($formInput, $formInputValue)
    {
        $record = Record::factory()->make([
            $formInput => $formInputValue
        ]);
        $this->signIn();
        $response = $this->post('/records', $record->toArray());

        $response->assertStatus(302);
        $response->assertSessionHasErrorsIn($formInput);
    }

    public function recordValidationProvider()
    {
        return [
            'artist id is required' => ['artist_id', ''],
            'artist id must exist in artists' => ['artist_id', 100],
            'title is required' => ['title', ''],
            'released is required' => ['released', ''],
            'released cant be earlier than 1800' => ['released', 1799],
            'released cant be more than one year in the future' => ['released', Carbon::now()->addYear(2)->year],
            'genre id is required' => ['genre_id', ''],
            'genre id must exist in genres' => ['genre_id', 100],
            'format id is required' => ['format_id', ''],
            'format id must exists in formats' => ['format_id', 100],
            'release code is required' => ['release_code', ''],
        ];
    }

    /**
     * @test
     * @dataProvider recordValidationProvider
     * @dataProvider recordUpdateProvider
     * @param $field
     * @param $fieldValue
     */
    public function update_validation($field, $fieldValue)
    {
        $this->signIn();
        $record = Record::factory()->create();
        $id = $record->id;
        $record[$field] = $fieldValue;
        $response = $this->put('/records/' . $id, $record->toArray());
        $response->assertStatus(302);
        $response->assertSessionHasErrorsIn($field);
    }

    public function recordUpdateProvider()
    {
        return [
            'id is  required' => ['id', null],
            'id must exist in records' => ['id', 100]
        ];
    }
}

