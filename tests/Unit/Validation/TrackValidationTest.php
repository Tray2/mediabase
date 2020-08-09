<?php

namespace Tests\Unit\Validation;

use App\Record;
use App\Track;
use Tests\TestCase;

class TrackValidationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        factory(Record::class)->create();
        $this->signIn();
    }

    /**
    * @test
    */
    public function a_valid_track_can_be_stored()
    {
        $track = factory(Track::class)->make([
            'track_no' => 2,
            'title' => 'King Of Rock',
            'mix' => 'Studio',
            'record_id' => 1
        ]);

        $this->post('/tracks', $track->toArray());
        $this->assertEquals(1, Track::where('title', $track->title)->count());
    }

    /**
     * @test
     * @dataProvider storeValidationProvider
     * @param $fieldValue
     * @param $field
     */
    public function store_validation_tests($field, $fieldValue)
    {
        $track = factory(Track::class)->make([
            $field => $fieldValue
        ]);

        $response = $this->post('/tracks', $track->toArray());
        $response->assertStatus(302);
        $response->assertSessionHasErrorsIn($field);
    }

    public function storeValidationProvider()
    {
        return [
            'the track number is required' => ['track_no', ''],
            'the title is required' => ['title', ''],
            'the mix is required' => ['mix', ''],
            'the record id is required' => ['record_id', ''],
            'the record id must exist in records' => ['record_id', 100]
        ];
    }

    /**
    * @test
    */
    public function a_valid_track_can_be_updatet()
    {
        $track = factory(Track::class)->create([
            'track_no' => 2,
            'title' => 'King Of Rock',
            'mix' => 'Studio',
            'record_id' => 1
        ]);

        $track->title = 'You Talk Too Much';

        $this->put('/tracks/' . $track->id, $track->toArray());
        $this->assertEquals(1, Track::where('title', $track->title)->count());
    }

    /**
     * @test
     * @dataProvider storeValidationProvider
     * @dataProvider updateValidationProvider
     * @param $field
     * @param $fieldValue
     */
    public function update_validation_tests($field, $fieldValue)
    {
        $track = factory(Track::class)->create();
        $id = $track->id;
        $track[$field] = $fieldValue;

        $response = $this->put('/tracks/' . $id, $track->toArray());
        $response->assertStatus(302);
        $response->assertSessionHasErrorsIn('$field');

    }

    public function updateValidationProvider()
    {
        return [
            'id is required' => ['id', null],
            'id must exist in tracks' => ['id', 100]
        ];
    }


}
