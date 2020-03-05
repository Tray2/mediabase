<?php

namespace Tests\Unit\Validation;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Author;

class AuthorValidationTest extends TestCase
{
    use RefreshDatabase;

    public function setUp():void
    {
        parent::setUp();
        $this->signIn();
    }

    /**
    * @test
    */
    public function a_valid_author_can_be_stored()
    {
        $author = factory(Author::class)->make([
            'first_name' => 'Robert',
            'last_name' => 'Jordan',
        ]);

        $this->post('/authors', $author->toArray());
        $this->assertEquals(1, Author::count());
    }

    /**
     * @test
     * @dataProvider authorValidationProvider
     * @param $field
     * @param $fieldValue
     */
    public function store_validations($field, $fieldValue)
    {
        $author = factory(Author::class)->make([
           $field => $fieldValue
        ]);

        $response = $this->post('/authors', $author->toArray());
        $response->assertStatus(302);
        $response->assertSessionHasErrorsIn('$field');
    }

    public function authorValidationProvider()
    {
        return [
            'First name is required' => ['first_name', ''],
            'Last name is required' => ['last_name', ''],
        ];
    }

    /**
    * @test
    */
    public function authors_name_must_be_unique_to_store_an_author()
    {
        factory(Author::class)->create([
            'first_name' => 'Robert',
            'last_name' => 'Jordan'
        ]);

        $author = factory(Author::class)->make([
            'first_name' => 'Robert',
            'last_name' => 'Jordan'
        ]);

        $this->post('authors', $author->toArray())->assertSessionHasErrors(['first_name' => 'Author name not unique']);

        $this->assertEquals(1, Author::count());
    }

    /**
    * @test
    */
    public function a_valid_author_can_be_updated()
    {
        $author = factory(Author::class)->create([
            'first_name' => 'Steven',
            'last_name' => 'Jordan'
        ]);

        $author->first_name = 'Robert';

        $this->put('/authors/' . $author->id, $author->toArray());
        $this->assertEquals('Jordan, Robert', $author->fresh()->name);
    }

    /**
     * @test
     * @dataProvider authorValidationProvider
     * @dataProvider authorIdProvider
     * @param $field
     * @param $fieldValue
     */
    public function update_validation($field, $fieldValue)
    {
        $author = factory(Author::class)->create();
        $id = $author->id;
        $author[$field] = $fieldValue;

        $response = $this->put('/authors/' . $id, $author->toArray());
        $response->assertStatus(302);
        $response->assertSessionHasErrorsIn('$field');
    }

    public function authorIdProvider()
    {
        return [
            'id is required' => ['id', null],
            'id must exist in authors' => ['id', 100]
        ];
    }

    /**
    * @test
    */
    public function author_name_must_be_unique_to_update_an_author()
    {
        factory(Author::class)->create([
            'first_name' => 'Robert',
            'last_name' => 'Jordan'
        ]);

        $author2 = factory(Author::class)->create([
              'first_name' => 'Steven',
              'last_name' => 'Jordan'
          ]);

        $author2->first_name = 'Robert';

        $this->put('/authors/' . $author2->id, $author2->toArray())->assertSessionHasErrors(['first_name' => 'Author name not unique']);
        $this->assertEquals(1, Author::where('first_name', 'Steven')->count());
    }

    /**
     *  @test
     */
    public function a_user_can_delete_an_author()
    {
        $author = factory(Author::class)->create();
        $this->assertEquals(1, Author::count());

        $this->delete('/authors/' . $author->id, ['id' => $author->id]);

        $this->assertEquals(0, Author::count());
    }
}
