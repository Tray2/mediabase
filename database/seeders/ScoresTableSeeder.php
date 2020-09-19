<?php
namespace Database\Seeders;

use App\Models\MediaType;
use App\Models\Score;
use Illuminate\Database\Seeder;

class ScoresTableSeeder extends Seeder
{

    protected $scores = [
        [
            'item_id' => 1,
            'score' => 4,
            'media' => 'Books'
        ],
        [
            'item_id' => 2,
            'score' => 3,
            'media' => 'Books'
        ],
        [
            'item_id' => 1,
            'score' => 5,
            'media' => 'Books'
        ],
        [
            'item_id' => 1,
            'score' => 2,
            'media' => 'Books'
        ],
        [
            'item_id' => 3,
            'score' => 1,
            'media' => 'Books'
        ],
        [
            'item_id' => 2,
            'score' => 2,
            'media' => 'Books'
        ],
        [
            'item_id' => 2,
            'score' => 3,
            'media' => 'Books'
        ],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
            foreach ($this->scores as $score) {
                $score['media_type_id'] = MediaType::where('media', $score['media'])->pluck('id')->first();
                unset($score['media']);
                Score::create($score);
            }
    }
}
