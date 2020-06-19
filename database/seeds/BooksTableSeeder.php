<?php

use App\AuthorBook;
use App\Format;
use App\Genre;
use App\Author;
use App\Book;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BooksTableSeeder extends Seeder
{
    protected $books = [
        [
            'author_first' => 'Robert',
            'author_last' => 'Jordan',
            'title' => 'The Eye Of The World',
            'series' => 'The Wheel Of Time',
            'part' => 1,
            'format' => 'Paperback',
            'genre' => 'Fantasy',
            'isbn' => '9780812511819',
            'released' => 1990,
            'reprinted' => 1990,
            'pages' => 814,
            'blurb' => 'The Wheel of Time turns and Ages come and go, leaving memories that become legend. Legend fades to myth, and even myth is long forgotten when the Age that gave it birth returns again. In the Third Age, an Age of Prophecy, the World and Time themselves hang in the balance. What was, what will be, and what is, may yet fall under the Shadow.'
        ],
        [
            'author_first' => 'Robert',
            'author_last' => 'Jordan',
            'title' => 'The Great Hunt',
            'series' => 'The Wheel Of Time',
            'part' => 2,
            'format' => 'Paperback',
            'genre' => 'Fantasy',
            'isbn' => '9780812517729',
            'released' => 1990,
            'reprinted' => 1991,
            'pages' => 70,
            'blurb' => 'The Wheel of Time turns and Ages come and pass. What was, what will be, and what is, may yet fall under the Shadow. Let the Dragon ride again on the winds of time.'
        ],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach($this->books as $book) {
            $format = Format::where('format', $book['format'])->where('type', 'books')->first();
            $genre = Genre::where('genre', $book['genre'])->where('type','books')->first();
            $author = Author::firstOrNew([
                'first_name' => $book['author_first'],
                'last_name' => $book['author_last'],
                'slug' => Str::slug($book['author_last'] . ' ' . $book['author_first'])
            ]);
            $bookData = array_merge($book, ['format_id' => $format->id, 'genre_id' => $genre->id ]);
            unset($bookData['author_first']);
            unset($bookData['author_last']);
            unset($bookData['format']);
            unset($bookData['genre']);
            $bookId = Book::create($bookData);
            AuthorBook::create(['author_id' => $author->id, 'book_id' => $bookId->id]);
         }
    }
}
