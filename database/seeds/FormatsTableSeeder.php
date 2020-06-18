<?php

use App\Format;
use Illuminate\Database\Seeder;

class FormatsTableSeeder extends Seeder
{
    protected $formats = [
      [
        'format' => 'Paperback',
        'type' => 'books'
      ],
      [
         'format' => 'Hardcover',
         'type' => 'books'
      ],
      [
         'format' => 'Audio',
         'type' => 'books'
      ],
      [
        'format' => 'E-book',
        'type' => 'books'
      ],
    ];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach($this->formats as $format) {
            Format::create($format);
        }
    }
}
