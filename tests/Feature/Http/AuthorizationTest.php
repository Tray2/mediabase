<?php

namespace Tests\Feature\Http;

use Tests\TestCase;

class AuthorizationTest extends TestCase
{
    public function protectedRoutesProvider()
    {
        return [
          'Artists: a guest is not authorized to visit the create page' => ['get', '/artists/create'],
          'Artists: a guest is not authorized to visit the edit page' => ['get', '/artists/1/edit'],
          'Artists: a guest is not authorized to visit the store page' => ['post', '/artists'],
          'Artists: a guest is not authorized to visit the update page' => ['put', '/artists/1'],
          'Artists: a guest is not authorized to visit the delete page' => ['delete', '/artists/1'],

          'Authors: a guest is not authorized to visit the create page' => ['get', '/authors/create'],
          'Authors: a guest is not authorized to visit the edit page' => ['get', '/authors/1/edit'],
          'Authors: a guest is not authorized to visit the store page' => ['post', '/authors'],
          'Authors: a guest is not authorized to visit the update page' => ['put', '/authors/1'],
          'Authors: a guest is not authorized to visit the delete page' => ['delete', '/authors/1'],

          'Books: a guest is not authorized to visit the create page' => ['get', '/books/create'],
          'Books: a guest is not authorized to visit the edit page' => ['get', '/books/1/edit'],
          'Books: a guest is not authorized to visit the store page' => ['post', '/books'],
          'Books: a guest is not authorized to visit the update page' => ['put', '/books/1'],
          'Books: a guest is not authorized to visit the delete page' => ['delete', '/books/1'],

          'Formats: a guest is not authorized to visit the create page' => ['get', '/formats/create'],
          'Formats: a guest is not authorized to visit the edit page' => ['get', '/formats/1/edit'],
          'Formats: a guest is not authorized to visit the store page' => ['post', '/formats'],
          'Formats: a guest is not authorized to visit the update page' => ['put', '/formats/1'],
          'Formats: a guest is not authorized to visit the delete page' => ['delete', '/formats/1'],

          'Genres: a guest is not authorized to visit the create page' => ['get', '/genres/create'],
          'Genres: a guest is not authorized to visit the edit page' => ['get', '/genres/1/edit'],
          'Genres: a guest is not authorized to visit the store page' => ['post', '/genres'],
          'Genres: a guest is not authorized to visit the update page' => ['put', '/genres/1'],
          'Genres: a guest is not authorized to visit the delete page' => ['delete', '/genres/1'],

          'Records: a guest is not authorized to visit the create page' => ['get', '/records/create'],
          'Records: a guest is not authorized to visit the edit page' => ['get', '/records/1/edit'],
          'Records: a guest is not authorized to visit the store page' => ['post', '/records'],
          'Records: a guest is not authorized to visit the update page' => ['put', '/records/1'],
          'Records: a guest is not authorized to visit the delete page' => ['delete', '/records/1'],

          'Tracks: a guest is not authorized to visit the create page' => ['get', '/tracks/create'],
          'Tracks: a guest is not authorized to visit the edit page' => ['get', '/tracks/1/edit'],
          'Tracks: a guest is not authorized to visit the store page' => ['post', '/tracks'],
          'Tracks: a guest is not authorized to visit the update page' => ['put', '/tracks/1'],
          'Trackss: a guest is not authorized to visit the delete page' => ['delete', '/tracks/1'],

          'BookCollection: a guest is not authorized to visit the store page' => ['post', '/bookcollections'],
          'BookCollection: a guest is not authorized to visit the delete page' => ['delete', '/bookcollections/1'],

          'BookRead: a guest is not authorized to visit the store page' => ['post', '/books/read'],
          'BookRead: a guest is not authorized to visit the delete page' => ['delete', '/books/read/1'],

          'UserPages: a guest is not authorized to visit the dashboard (home) page' => ['get', '/home']


        ];
    }

    /**
     * @test
     * @dataProvider protectedRoutesProvider
     * @param $method
     * @param $route
     */
    public function guests_are_redirected_to_login($method, $route)
    {
        $response = $this->$method($route);
        $response->assertLocation('/login');
    }
}
