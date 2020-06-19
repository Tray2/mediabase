<?php

use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Test User',
            'slug' => 'test-user',
            'email' => 'test.user@mediabase.test',
            'password' => bcrypt('secret')
        ]);
    }
}
