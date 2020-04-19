<?php

namespace Tests\Unit\Validation;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;
use App\Artist;

class ArtistValidationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp():void
    {
        parent::setUp();
        $this->signIn();
    }

    /**
    * @test
    */
    public function a_valid_artist_can_be_stored()
    {
        $this->withoutExceptionHandling();
        $artist = factory(Artist::class)->make([
            'name' => 'Run Dmc',
            'slug' => 'run-dmc'
        ]);

        $this->post('/artists', $artist->toArray());
        $this->assertEquals(1, Artist::count());
    }

    /**
     * @test
     * @dataProvider artistValidationProvider
     * @param $field
     * @param $fieldValue
     */
    public function artist_validations($field, $fieldValue)
    {
        factory(Artist::class)->create([
            'name' => 'Run Dmc'
        ]);
        $artist = factory(Artist::class)->make([
            'name' => $fieldValue
        ]);

        $response = $this->post('/artists', $artist->toArray());
        $response->assertStatus(302);
        $response->assertSessionHasErrorsIn('name');
    }

    public function artistValidationProvider()
    {
        return [
          'name is required' => ['name', ''],
          'name must be unique' => ['name', 'Run Dmc']
        ];
    }

    /** @test */
    public function a_valid_artist_can_be_updated()
    {
        $this->withoutExceptionHandling();
        $artist = factory(Artist::class)->create();
        $artist->name = 'Erik B & Rakim';

        $this->put('/artists/' . $artist->id, $artist->toArray());
        $this->assertEquals(1, Artist::where('name', 'Erik B & Rakim')->count());
        $this->assertEquals(1, Artist::where('slug', 'erik-b-rakim')->count());
    }

    /**
     * @test
     * @dataProvider artistValidationProvider
     * @dataProvider updateArtistValidationProvider
     * @param $field
     * @param $fieldValue
     */
    public function update_artist_validations($field, $fieldValue)
    {
        factory(Artist::class)->create([
            'name' => 'Run Dmc'
        ]);

        $artist = factory(Artist::class)->create();
        $id = $artist->id;
        $artist[$field] = $fieldValue;

        $response = $this->put('/artists/' . $id, $artist->toArray());
        $response->assertStatus(302);
        $response->assertSessionHasErrorsIn($field);
    }

    public function updateArtistValidationProvider()
    {
        return [
            'id is required' => ['id', ''],
            'id must exists in artists' => ['id', 100]
        ];
    }

    /** @test */
    public function a_user_can_delete_a_artist()
    {
        $artist = factory(Artist::class)->create();
        $this->delete('/artists/' . $artist->id, ['id' => $artist->id]);
        $this->assertEquals(0, Artist::count());
    }
}
