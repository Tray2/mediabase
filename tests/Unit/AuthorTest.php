<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Author;

class AuthorTest extends TestCase
{
    /**
    * @test
    */
    public function name_property_returns_the_authors_last_name_and_first_name()
    {
        $author = Author::factory()->make([
            'first_name' => 'Robert',
            'last_name' => 'Jordan'
        ]);

        $this->assertEquals('Jordan, Robert', $author->name);
    }

    /**
    * @test
    */
    public function when_listing_all_authors_they_should_be_sorted_alphabetically_by_last_name_then_first_name()
    {
        Author::factory()->create([
            'first_name' => 'Steven',
            'last_name' => 'Jordan'
        ]);

        Author::factory()->create([
            'first_name' => 'Robert',
            'last_name' => 'Jordan'
        ]);

        $response = $this->get('/authors');

        $response->assertSeeInOrder(['Jordan, Robert', 'Jordan, Steven']);
    }

}
