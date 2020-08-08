<?php

use App\MediaType;
use Illuminate\Database\Seeder;

class MediaTypeSeeder extends Seeder
{
    protected $mediaTypes = [
        [
            'media' => 'Books'
        ],
        [
            'media' => 'Games'
        ],
        [
            'media' => 'Movies'
        ],
        [
            'media' => 'Records'
        ],

    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach($this->mediaTypes as $media) {
            MediaType::create($media);
        }
    }

}
