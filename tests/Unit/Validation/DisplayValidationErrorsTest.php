<?php

namespace Tests\Unit\Validation;

use Tests\TestCase;
use App\Author;

class DisplayValidationErrorsTest extends TestCase
{
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
