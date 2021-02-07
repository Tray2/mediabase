<?php

namespace Tests\Feature\Http\GenresControllerTest;

use App\Models\Artist;
use App\Models\Author;
use App\Models\AuthorBook;
use App\Models\Book;
use App\Models\Format;
use App\Models\Genre;
use App\Models\Record;
use Tests\TestCase;

class GenresControllerShowTest extends TestCase
{
    /**
     *  @test
     */
    public function a_guest_can_visit_a_genre_and_see_all_the_books_belonging_to_it()
    {
        $genre1 = Genre::factory()->create();
        $genre2 = Genre::factory()->create();
        Author::factory()->create();
        Format::factory()->create();
        $book1Genre1 = Book::factory()->create(['title' => 'The Eye Of The World', 'genre_id' => $genre1->id]);
        $book2Genre1 = Book::factory()->create(['title' => 'The Great Hunt', 'genre_id' => $genre1->id]);
        $book3Genre2 = Book::factory()->create(['title' => 'Laravel Up & Running', 'genre_id' => $genre2->id]);

        $response = $this->get('/genres/' . $genre1->id);

        $response->assertSee(htmlentities($genre1->genre, ENT_QUOTES));
        $response->assertSee($book1Genre1->title);
        $response->assertSee($book2Genre1->title);
        $response->assertDontSee($book3Genre2->title);
    }

    /**
    * @test
    */
    public function the_books_are_sorted_by_author_series_started_part_released_and_title()
    {
        Format::factory()->create();
        $genre = Genre::factory()->create(
            [
                'genre' => 'Fantasy',
                'media_type_id' => env('BOOKS')
            ]
        );
        $robertJordan = Author::factory()->create(
            [
                'first_name' => 'Robert',
                'last_name' => 'Jordan'
            ]
        );
        $davidEddings = Author::factory()->create(
            [
                'first_name' => 'David',
                'last_name' => 'Eddings'
            ]
        );
        $terryBrooks = Author::factory()->create(
            [
                'first_name' => 'Terry',
                'last_name' => 'Brooks'
            ]
        );
        $terryPratchet = Author::factory()->create(
            [
                'first_name' => 'Terry',
                'last_name' => 'Pratchett'
            ]
        );

        $theWheelOfTime1 = Book::factory()->create(
            [
                'title' => 'The Eye Of The World',
                'series' => 'The Wheel Of Time',
                'part' => 1,
                'released' => 1991,
                'genre_id' => $genre->id
            ]
        );
        $theWheelOfTime2 = Book::factory()->create(
            [
                'title' => 'The Great Hunt',
                'series' => 'The Wheel Of Time',
                'part' => 2,
                'released' => 1992,
                'genre_id' => $genre->id
            ]
        );
        $theDiscworld = Book::factory()->create(
            [
                'title' => 'Guards, Guards',
                'series' => 'The Discworld',
                'part' => 6,
                'released' => 1991,
                'genre_id' => $genre->id
            ]
        );
        $theBelgariad = Book::factory()->create(
            [
                'title' => 'Pawn Of Prophecy',
                'series' => 'The Belgariad',
                'part' => 1,
                'released' => 1987,
                'genre_id' => $genre->id
            ]
        );
        $shannara = Book::factory()->create(
            [
                'title' => 'The Sword Of Shannara',
                'series' => 'Shannara',
                'part' => 1,
                'released' => 1986,
                'genre_id' => $genre->id
            ]
        );

        AuthorBook::factory()->create(
            [
                'author_id' => $robertJordan->id,
                'book_id' => $theWheelOfTime1->id
            ]
        );
        AuthorBook::factory()->create(
            [
                'author_id' => $robertJordan->id,
                'book_id' => $theWheelOfTime2->id
            ]
        );
        AuthorBook::factory()->create(
            [
                'author_id' => $terryBrooks->id,
                'book_id' => $shannara->id
            ]
        );
        AuthorBook::factory()->create(
            [
                'author_id' => $davidEddings->id,
                'book_id' => $theBelgariad->id
            ]
        );
        AuthorBook::factory()->create(
            [
                'author_id' => $terryPratchet->id,
                'book_id' => $theDiscworld->id
            ]
        );

        $response = $this->get('/genres/' . $genre->id . '?type=BOOKS');

        $response->assertSeeTextInOrder([
           'Brooks, Terry', 'The Sword Of Shannara',
           'Eddings, David', 'Pawn Of Prophecy',
           'Jordan, Robert', 'The Eye Of The World',
           'Jordan, Robert', 'The Great Hunt',
           'Pratchett, Terry', 'Guards, Guards'
        ]);
    }

    /**
    * @test
    */
    public function the_records_are_sorted_by_artist_released_and_year()
    {
        Format::factory()->create();
        $genre = Genre::factory()->create(
            [
                'genre' => 'Hip Hop',
                'media_type_id' => env('RECORDS')
            ]
        );
        $runDmc = Artist::Factory()->create(['name' => 'Run Dmc']);
        $iceT = Artist::factory()->create(['name' => 'Ice-T']);

        Record::factory()->create(
            [
              'title' => 'Tougher Than Leather',
              'artist_id' => $runDmc->id,
              'released' => 1988
            ]
        );
        Record::factory()->create(
            [
                'title' => 'King Of Rock',
                'artist_id' => $runDmc->id,
                'released' => 1986
            ]
        );
        Record::factory()->create(
            [
                'title' => 'Original Gangster',
                'artist_id' => $iceT->id,
                'released' => 1991
            ]
        );

        $response = $this->get('/genres/' . $genre->id . '?type=RECORDS');

        $response->assertSeeTextInOrder([
            'Ice-T', 'Original Gangster', '1991',
            'Run Dmc', 'King Of Rock', '1986',
            'Run Dmc', 'Tougher Than Leather', '1988'
        ]);
    }
}
