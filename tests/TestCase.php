<?php

namespace Tests;

use Database\Seeders\MediaTypeSeeder;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public $seeder = MediaTypeSeeder::class;
}
