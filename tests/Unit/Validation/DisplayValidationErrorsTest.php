<?php

namespace Tests\Unit\Validation;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Author;

class DisplayValidationErrorsTest extends TestCase
{
    use DatabaseMigrations;
    
    /**
    * @test
    */
    public function validation_errors_are_displayed()
    {
        $this->signIn();
        $author = factory(Author::class)->make([
            'first_name' => 'Robert',
            'last_name' => null
        ]);

        $this->post('/authors', $author->toArray());
        $response = $this->get('/authors/create');
        $response->assertSee('The last name field is required.');
    }
}
