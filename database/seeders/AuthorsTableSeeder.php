<?php
namespace Database\Seeders;

use App\Models\Author;
use Illuminate\Database\Seeder;

class AuthorsTableSeeder extends Seeder
{
    protected $authors = [
        [
            'first_name' => 'Robert',
            'last_name' => 'Jordan',
            'slug' => 'jordan-robert'
        ],
        [
            'first_name' => 'Terry',
            'last_name' => 'Goodkind',
            'slug' => 'goodkind-terry'
        ],
        [
            'first_name' => 'Patricia',
            'last_name' => 'Briggs',
            'slug' => 'briggs-patricia'
        ],
        [
            'first_name' => 'Anne',
            'last_name' => 'Bishop',
            'slug' => 'bishop-anne'
        ],
        [
            'first_name' => 'Sarah',
            'last_name' => 'Ash',
            'slug' => 'ash-sarah'
        ],
        [
            'first_name' => 'Faith',
            'last_name' => 'Hunter',
            'slug' => 'hunter-faith'
        ],
    ];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach($this->authors as $author) {
            Author::create($author);
        }
    }
}
