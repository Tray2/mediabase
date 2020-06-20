<?php

use App\Score;
use Illuminate\Database\Seeder;

class ScoresTableSeeder extends Seeder
{

    protected $scores = [
        [
            'book_id' => 1,
            'score' => 4
        ],
        [
            'book_id' => 2,
            'score' => 3
        ],
        [
            'book_id' => 1,
            'score' => 5
        ],
        [
            'book_id' => 1,
            'score' => 2
        ],
        [
            'book_id' => 3,
            'score' => 1
        ],
        [
            'book_id' => 2,
            'score' => 2
        ],
        [
            'book_id' => 2,
            'score' => 3
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
                Score::create($score);
            }
    }
}
