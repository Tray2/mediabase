<?php

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
        $this->call(UsersTableSeeder::class);
        //$this->call(AuthorsTableSeeder::class);
        $this->call(FormatsTableSeeder::class);
        $this->call(GenresTableSeeder::class);
        $this->call(BooksTableSeeder::class);
        $this->call(ScoresTableSeeder::class);
    }
}
