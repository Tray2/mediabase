<?php

namespace Tests\Unit;

use App\Models\Book;
use Tests\TestCase;
use App\Models\Format;

class FormatTest extends TestCase
{
    /**
    * @test
    */
    public function the_format_must_start_every_word_with_an_upper_case_letter()
    {
        $format = Format::factory()->make([
            'format' => 'paPerBack',
            'media_type_id' => env('BOOKS')
        ]);

        $this->assertEquals('Paperback', $format->format);
    }

    /**
    * @test
    */
    public function formats_should_be_sorted_alphabeticaly()
    {
        Format::factory()->create([
            'format' => 'Hardcover',
            'media_type_id' => env('BOOKS')
        ]);

        Format::factory()->create([
            'format' => 'Paperback',
            'media_type_id' => env('BOOKS')
        ]);

        Format::factory()->create([
            'format' => 'Big Pocket',
            'media_type_id' => env('BOOKS')
        ]);

        $response = $this->get('/formats');
        $response->assertSeeInOrder(['Big Pocket', 'Hardcover', 'Paperback']);
    }

    /**
    * @test
    */
    public function formats_are_ordered_by_type_then_the_format()
    {
        Format::factory()->create([
            'format' => 'Hardcover',
            'media_type_id' => env('BOOKS')
        ]);

        Format::factory()->create([
            'format' => 'Cd',
            'media_type_id' => env('RECORDS')
        ]);

        Format::factory()->create([
            'format' => 'Lp',
            'media_type_id' => env('RECORDS')
        ]);

        Format::factory()->create([
            'format' => 'Big Pocket',
            'media_type_id' => env('BOOKS')
        ]);

        $response = $this->get('/formats');
        $response->assertSeeInOrder(['Big Pocket', 'Hardcover', 'Cd', 'Lp']);
    }

    /**
    * @test
    */
    public function the_format_type_is_shown_on_the_index_page()
    {
        $this->withoutExceptionHandling();
        Format::factory()->create([
            'format' => 'Hardcover',
            'media_type_id' => env('BOOKS')
        ]);

        Format::factory()->create([
            'format' => 'Cd',
            'media_type_id' => env('RECORDS')
        ]);
        $response = $this->get('/formats');
        $response->assertSeeInOrder(['<td>Books</td>', '<td>Records</td>'], false);
    }
}
