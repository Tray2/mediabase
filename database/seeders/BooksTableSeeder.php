<?php
namespace Database\Seeders;

use App\Models\Author;
use App\Models\AuthorBook;
use App\Models\Book;
use App\Models\Format;
use App\Models\Genre;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BooksTableSeeder extends Seeder
{
    protected $books = [
        [
            'authors' => [
                [
                    'first_name' => 'Robert',
                    'last_name' => 'Jordan',
                ]
            ],
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
            'authors' => [
                [
                    'first_name' => 'Robert',
                    'last_name' => 'Jordan',
                ]
            ],
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
        [
            'authors' => [
                [
                    'first_name' => 'Robert',
                    'last_name' => 'Jordan',
                ],
                [
                    'first_name' => 'Brandon',
                    'last_name' => 'Sanderson'
                ]
            ],
            'title' => 'A Memory Of Light',
            'series' => 'The Wheel Of Time',
            'part' => 14,
            'format' => 'Hardcover',
            'genre' => 'Fantasy',
            'isbn' => '9780765325952',
            'released' => 2013,
            'reprinted' => null,
            'pages' => 700,
            'blurb' => 'The Wheel of Time turns and Ages come and go, leaving memories that become legend. Legend fades to myth, and even myth is long forgotten when the Age that gave it birth returns again. In the Third Age, an Age of Prophecy, the World and Time themselves hang in the balance. What was, what will be, and what is, may yet fall under the Shadow.'
        ],
        [
            'authors' => [
                [
                    'first_name' => 'David',
                    'last_name' => 'Eddings',
                ]
            ],
            'title' => 'Pawn Of Prophecy',
            'series' => 'The Balgariad',
            'part' => 1,
            'format' => 'Paperback',
            'genre' => 'Fantasy',
            'isbn' => '9780345468642',
            'released' => 1982,
            'reprinted' => 2004,
            'pages' => 304,
            'blurb' => 'A magnificent epic set against a history of seven thousand years of the struggles of Gods and Kings and men - of strange lands and events - of fate and a prophecy that must be fulfilled! THE BELGARIAD Long ago, so the Storyteller claimed, the evil God Torak sought dominion and drove men and Gods to war. But Belgarath the Sorcerer led men to reclaim the Orb that protected men of the West. So long as it lay at Riva, the prophecy went, men would be safe. But that was only a story, and Garion did not believe in magic dooms, even though the dark man without a shadow had haunted him for years. Brought up on a quiet farm by his Aunt Pol, how could he know that the Apostate planned to wake dread Torak, or that he would be led on a quest of unparalleled magic and danger by those he loved - but did not know? For a while his dreams of innocence were safe, untroubled by knowledge of his strange heritage. For a little while... THUS BEGINS BOOK ONE OF THE BELGARIAD'
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
            $format = Format::where('format', $book['format'])->first();
            $genre = Genre::where('genre', $book['genre'])->first();
            $bookData = array_merge($book, ['format_id' => $format->id, 'genre_id' => $genre->id ]);
            unset($bookData['authors']);
            unset($bookData['format']);
            unset($bookData['genre']);
            $bookRecord = Book::create($bookData);

            foreach($book['authors'] as $author) {
                $authorRecord = Author::firstOrNew([
                    'first_name' => $author['first_name'],
                    'last_name' => $author['last_name'],
                    'slug' => Str::slug($author['last_name'] . ' ' . $author['first_name'])
                ]);
                $authorRecord->save();
                AuthorBook::create([
                    'author_id' => $authorRecord->id,
                    'book_id' => $bookRecord->id
                ]);
            }

         }
    }
}
