<?php

namespace Tests\Unit\Validation;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Format;

class FormatValidationTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->signIn();
    }

    /**
    * @test
    */
    public function it_can_store_a_valid_format()
    {
        $this->withoutExceptionHandling();
        $format = factory(Format::class)->make([
            'format' => 'Paperback',
            'type' => 'book'
        ]);

        $this->post('/formats', $format->toArray());
        $this->assertEquals(1, Format::where('type', 'book')->count());
    }

    /**
     * @test
     * @dataProvider FormatValidationProvider
     * @param $field
     * @param $fieldValue
     */
    public function store_validations($field, $fieldValue)
    {
        factory(Format::class)->create([
            'format' => 'Paperback',
            'type' => 'book'
        ]);

        $format = factory(Format::class)->make([
            $field => $fieldValue
        ]);

        $response = $this->post('/formats', $format->toArray());
        $response->assertStatus(302);
        $response->assertSessionHasErrorsIn('$field');
    }

    public function formatValidationProvider()
    {
        return [
            'format is required' => ['format', ''],
            'format must be unique' => ['format', 'Paperback'],
        ];
    }

    /** @test */
    public function a_valid_format_can_be_updated()
    {
        $format = factory(Format::class)->create();
        $format->format = 'Paperback';

        $this->put('/formats/' . $format->id, $format->toArray());
        $this->assertEquals(1, Format::where('format', 'Paperback')->count());
    }

    /**
     * @test
     * @dataProvider formatValidationProvider
     * @dataProvider formatUpdateProvider
     * @param $field
     * @param $fieldValue
     */
    public function update_validations($field, $fieldValue)
    {
        factory(Format::class)->create(['format' => 'Paperback']);
        $format = factory(Format::class)->create();
        $id = $format->id;
        $format[$field] = $fieldValue;
        $response = $this->put('/formats/' . $id);
        $response->assertStatus(302);
        $response->assertSessionHasErrorsIn($field);

    }

    public function formatUpdateProvider()
    {
        return [
            'id is  required' => ['id', null],
            'id must exist in formats' => ['id', 100]
        ];
    }

    /** @test */
    public function a_user_can_delete_a_format()
    {
        $format = factory(Format::class)->create();

        $this->delete('/formats/' . $format->id, ['id' => $format->id]);
        $this->assertEquals(0, Format::count());
    }
}
