<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Format;

class FormatTest extends TestCase
{
    use RefreshDatabase;

    /**
    * @test
    */
    public function the_format_must_start_every_word_with_an_upper_case_letter()
    {
        $this->signIn();

        $format = factory(Format::class)->make([
            'format' => 'paPerBack',
            'type' => 'books'
        ]);

        $this->post('/formats', $format->toArray());
        $this->assertEquals(1, Format::where('format', 'Paperback')->count());
    }

    /**
    * @test
    */
    public function formats_should_be_sorted_alphabeticaly()
    {
        factory(Format::class)->create([
            'format' => 'Hardcover',
            'type' => 'books'
        ]);

        factory(Format::class)->create([
            'format' => 'Paperback',
            'type' => 'books'
        ]);

        factory(Format::class)->create([
            'format' => 'Big Pocket',
            'type' => 'books'
        ]);

        $response = $this->get('/formats');
        $response->assertSeeInOrder(['Big Pocket', 'Hardcover', 'Paperback']);
    }

    /**
    * @test
    */
    public function formats_are_ordered_by_type_then_the_format()
    {
        factory(Format::class)->create([
            'format' => 'Hardcover',
            'type' => 'books'
        ]);

        factory(Format::class)->create([
            'format' => 'Cd',
            'type' => 'records'
        ]);

        factory(Format::class)->create([
            'format' => 'Lp',
            'type' => 'records'
        ]);

        factory(Format::class)->create([
            'format' => 'Big Pocket',
            'type' => 'books'
        ]);

        $response = $this->get('/formats');
        $response->assertSeeInOrder(['Big Pocket', 'Hardcover', 'Cd', 'Lp']);
    }

    /**
    * @test
    */
    public function the_format_type_is_shown_on_the_index_page()
    {
        factory(Format::class)->create([
            'format' => 'Hardcover',
            'type' => 'books'
        ]);

        factory(Format::class)->create([
            'format' => 'Cd',
            'type' => 'records'
        ]);
        $response = $this->get('/formats');
        $response->assertSeeInOrder(['<td>Books</td>', '<td>Records</td>'], false);
    }

}
