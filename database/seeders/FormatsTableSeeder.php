<?php
namespace Database\Seeders;

use App\Models\Format;
use App\Models\MediaType;
use Illuminate\Database\Seeder;

class FormatsTableSeeder extends Seeder
{
    protected $formats = [
      [
         'format' => 'Paperback',
         'media' => 'Books'
      ],

      [
         'format' => 'Hardcover',
         'media' => 'Books'
      ],
      [
         'format' => 'Audio',
         'media' => 'Books'
      ],
      [
        'format' => 'E-book',
        'media' => 'Books'
      ],
      [
        'format' => 'LP',
        'media' => 'Records'
      ]
    ];


    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach($this->formats as $format) {
            $format['media_type_id'] = MediaType::where('media', $format['media'])->pluck('id')->first();
            unset($format['media']);
            Format::create($format);
        }
    }
}
