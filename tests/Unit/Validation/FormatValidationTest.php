<?php

namespace Tests\Unit\Validation;

use Tests\TestCase;
use App\Models\Format;

class FormatValidationTest extends TestCase
{
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
        $format = Format::factory()->make([
            'format' => 'Paperback',
            'media_type_id' => env('BOOKS')
        ]);

        $this->post('/formats', $format->toArray());
        $this->assertEquals(1, Format::where('media_type_id', env('BOOKS'))->count());
    }

    /**
     * @test
     * @dataProvider FormatValidationProvider
     * @param $field
     * @param $fieldValue
     */
    public function store_validations($field, $fieldValue)
    {
        Format::factory()->create([
            'format' => 'Paperback',
            'media_type_id' => env('BOOKS')
        ]);

        $format = Format::factory()->make([
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
        $format = Format::factory()->create();
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
        Format::factory()->create(['format' => 'Paperback']);
        $format = Format::factory()->create();
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
        $format = Format::factory()->create();

        $this->delete('/formats/' . $format->id, ['id' => $format->id]);
        $this->assertEquals(0, Format::count());
    }
}
