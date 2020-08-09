<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use MediaTypeSeeder;


abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use RefreshDatabase;

    protected function setUp(): void
    {
        Parent::setUp();
        $this->seed(MediaTypeSeeder::class);
    }

    protected function signIn($user = null)
    {
        $user = $user ?: factory(\App\User::class)->create();
        $this->actingAs($user);
        return $this;
    }
}
