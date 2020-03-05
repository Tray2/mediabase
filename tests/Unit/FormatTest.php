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
            'type' => 'book'
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
            'type' => 'book'
        ]);

        factory(Format::class)->create([
            'format' => 'Paperback',
            'type' => 'book'
        ]);

        factory(Format::class)->create([
            'format' => 'Big Pocket',
            'type' => 'book'
        ]);

        $response = $this->get('/formats');
        $response->assertSeeInOrder(['Big Pocket', 'Hardcover', 'Paperback']);
    }

}
