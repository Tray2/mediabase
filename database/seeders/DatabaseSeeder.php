<?php

use Database\Seeders\AuthorsTableSeeder;
use Database\Seeders\BooksTableSeeder;
use Database\Seeders\FormatsTableSeeder;
use Database\Seeders\GenresTableSeeder;
use Database\Seeders\MediaTypeSeeder;
use Database\Seeders\ScoresTableSeeder;
use Database\Seeders\UsersTableSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(MediaTypeSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(AuthorsTableSeeder::class);
        $this->call(FormatsTableSeeder::class);
        $this->call(GenresTableSeeder::class);
        $this->call(BooksTableSeeder::class);
        $this->call(ScoresTableSeeder::class);
    }
}
