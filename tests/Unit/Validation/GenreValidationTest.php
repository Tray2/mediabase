<?php

namespace Tests\Unit\Validation;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Genre;

class GenreValidationTest extends TestCase
{
    use RefreshDatabase;

    public function setUp():void
    {
        parent::setUp();
        $this->signIn();
        factory(Genre::class)->create([
            'genre' => 'Fantasy'
        ]);
    }

    /**
    * @test
    */
    public function it_can_store_a_valid_genre()
    {
        $this->withoutExceptionHandling();
        $genre = factory(Genre::class)->make([
            'genre' => 'Crime',
            'type' => 'book'
        ]);

        $this->post('/genres', $genre->toArray())
            ->assertSessionDoesntHaveErrors('genre')
            ->assertSessionDoesntHaveErrors('type');
        $this->assertEquals(1, Genre::where('genre', 'Crime')->count());
    }

    /** @test */
    public function a_valid_genre_can_be_updated()
    {
        $genre = factory(Genre::class)->create();
        $genre->genre = 'Horror';

        $this->put('/genres/' . $genre->id, $genre->toArray())
            ->assertSessionDoesntHaveErrors('genre')
            ->assertSessionDoesntHaveErrors('type');
        $this->assertEquals(1, Genre::where('genre', 'Horror')->count());
    }

    /**
     * @test
     * @dataProvider genreValidationProvider
     * @param $field
     * @param $fieldValue
     */
    public function genre_validations($field, $fieldValue)
    {
        $genre = factory(Genre::class)->make([
            $field => $fieldValue
        ]);

        $this->post('/genres', $genre->toArray())
            ->assertStatus(302)
            ->assertSessionHasErrorsIn($field);
    }

    public function genreValidationProvider()
    {
        return [
            'genre is required' => ['genre', ''],
            'genre must be unique' => ['genre', 'Fantasy'],
            'type is required' => ['type', '']
        ];
    }

    /**
     * @test
     * @dataProvider genreValidationProvider
     * @dataProvider updateValidationProvider
     * @param $field
     * @param $fieldValue
     */
    public function update_validations($field, $fieldValue)
    {
        $genre = factory(Genre::class)->create();
        $id = $genre->id;
        $genre[$field] = $fieldValue;

        $this->put('/genres/' . $id, $genre->toArray())
            ->assertStatus(302)
            ->assertSessionHasErrorsIn($field);

    }

    public function updateValidationProvider()
    {
        return [
            'id is required' => ['id', ''],
            'id must exist in genres' => ['id',100]
        ];
    }

    /**
     * @test
     **/
    public function a_user_can_delete_a_genre()
    {
        $genre = factory(Genre::class)->create();

        $this->delete('/genres/' . $genre->id);
        $this->assertEquals(0, Genre::where('genre', $genre->genre)->count());
    }
}
