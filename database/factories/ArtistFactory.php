<?php
namespace Database\Factories;


use App\Models\Artist;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ArtistFactory extends Factory
{
    protected $model = Artist::class;

    public function definition()
    {
        $name = $this->faker->name();
        return [
            'name' => $name,
            'slug' => Str::slug($name)
        ];

    }
}
