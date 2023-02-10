<?php

use App\Models\Author;
use App\Models\Book;
use App\Models\Format;
use App\Models\Genre;
use App\Models\MediaType;
use App\Models\Series;
use App\Models\User;
use Database\Seeders\MediaTypeSeeder;
use Sinnbeck\DomAssertions\Asserts\AssertElement;
use function Pest\Laravel\get;

beforeEach(function () {
    $this->mediaTypeId = MediaType::query()
        ->where('name', 'book')
        ->value('id');
});

it('lists books', function () {
    $genre = Genre::factory()->create([
        'media_type_id' => $this->mediaTypeId,
    ]);
    $format = Format::factory()->create([
        'media_type_id' => $this->mediaTypeId,
    ]);
    $series = Series::factory()->create();
    $fields = ['title', 'published_year', 'part'];
    [$book1, $book2] = Book::factory()->count(2)->create([
        'genre_id' => $genre->id,
        'format_id' => $format->id,
        'series_id' => $series->id,
    ]);
    get(route('books.index'))
        ->assertOk()
        ->assertSeeText([
            ...$book1->only($fields),
            ...$book2->only($fields),
            $genre->name,
            $format->name,
            $series->name,
        ]);
});

it('sorts books by author', function () {
    Author::factory()
        ->count(2)
        ->sequence(
            ['first_name' => 'David', 'last_name' => 'Eddings'],
            ['first_name' => 'Sarah', 'last_name' => 'Ash']
        )->has(Book::factory())
        ->create();

    get(route('books.index'))
        ->assertOk()
        ->assertSeeTextInOrder([
            'Ash, Sarah',
            'Eddings, David',
        ]);
});

it('sorts books in the same series by part', function () {
    Author::factory()
        ->has(Book::factory()
        ->count(3)
        ->sequence(
            ['part' => 2, 'published_year' => 1990],
            ['part' => 1, 'published_year' => 1989],
            ['part' => 3, 'published_year' => 1991]
        )
       ->for(Series::factory([
           'name' => 'The Second Series',
       ]))
        )
        ->create();

    get(route('books.index'))
        ->assertOk()
        ->assertSeeTextInOrder([1989, 1990, 1991]);
});

it('sorts series of the same author by the published year of the first book in the series', function () {
    Author::factory()
        ->has(Book::factory()
            ->count(3)
            ->sequence(
                ['part' => 2, 'published_year' => 1971],
                ['part' => 1, 'published_year' => 1970],
                ['part' => 3, 'published_year' => 1972]
            )
            ->for(Series::factory([
                'name' => 'The Second Series',
            ]))
        )
        ->create([
            'first_name' => 'Ben',
            'last_name' => 'Something',
        ]);

    Author::factory()
        ->has(Book::factory()
            ->count(3)
            ->sequence(
                ['part' => 2, 'published_year' => 1970],
                ['part' => 1, 'published_year' => 1968],
                ['part' => 3, 'published_year' => 1971]
            )
            ->for(Series::factory([
                'name' => 'The First Series',
            ]))
        )
        ->create([
            'first_name' => 'Ben',
            'last_name' => 'Something',
        ]);

    get(route('books.index'))
        ->assertOk()
        ->assertSeeTextInOrder([
            '1968', 'The First Series',
            '1970', 'The First Series',
            '1971', 'The First Series',
            '1970', 'The Second Series',
            '1971', 'The Second Series',
            '1972', 'The Second Series',
        ]);
});

it('sorts a standalone book by the same author as a part of a Standalone series', function () {
    Author::factory()
        ->has(Book::factory()
            ->count(3)
            ->sequence(
                ['part' => 2, 'published_year' => 1971],
                ['part' => 1, 'published_year' => 1970],
                ['part' => 3, 'published_year' => 1972]
            )
            ->for(Series::factory(['name' => 'Second Series']))
        )
        ->create([
            'first_name' => 'Ben',
            'last_name' => 'Something',
        ]);

    Author::factory()
        ->has(Book::factory([
            'part' => null,
            'published_year' => 1971,
        ])
            ->for(Series::factory(['name' => 'Standalone']))
        )
        ->create([
            'first_name' => 'Ben',
            'last_name' => 'Something',
        ]);

    get(route('books.index'))
        ->assertOk()
        ->assertSeeTextInOrder([
            '1970', 'Second Series',
            '1971', 'Second Series',
            '1972', 'Second Series',
            '1971', 'Standalone',
        ]);
});

it('sorts books with multiple authors by the first author in alphabetical order', function () {
    [$author1, $author2] = Author::factory()
        ->count(2)
        ->sequence(
            ['first_name' => 'Robert', 'last_name' => 'Jordan'],
            ['first_name' => 'Brandon', 'last_name' => 'Sanderson']
        )
        ->create();

    $book = Book::factory()->create();
    $book->authors()->attach($author1);
    $book->authors()->attach($author2);

    get(route('books.index'))
        ->assertOk()
        ->assertSeeTextInOrder([
            'Jordan, Robert',
            'Sanderson, Brandon',
        ]);
});

it('display a link to the books.create route when a user is signed in', function () {
    actingAs(User::factory()->create())
        ->get(route('books.index'))
        ->assertElementExists(function(AssertElement $element) {
            $element->contains('a', ['href' => route('books.create')]);
        });
});

it('does not display a link to the books.create route for a guest', function () {
    get(route('books.index'))
        ->assertElementExists(function(AssertElement $element) {
            $element->doesntContain('a', ['href' => route('books.create')]);
        });
});

it('has a link to the books.show route for each title', function () {
    $this->seed(MediaTypeSeeder::class);
    $book = Book::factory()->create();
    get(route('books.index'))
        ->assertElementExists(function(AssertElement $element) use($book) {
            $element->contains('a', ['href' => route('books.show', $book->id)]);
        });
});

it('has a link to filter on authors', function () {
    $this->seed(MediaTypeSeeder::class);
    $book = Book::factory()->create();
    $author = Author::factory()->create();
    $book->authors()->attach($author->id);
    get(route('books.index'))
        ->assertElementExists(function(AssertElement $element) use($author) {
            $element->contains('a', ['href' => route('books.index', ['authors' => $author->id])]);
        });
});

it('has a link to filter on multiple authors', function () {
    $this->seed(MediaTypeSeeder::class);
    $book = Book::factory()->create();
    $author1 = Author::factory()->create();
    $author2 = Author::factory()->create();

    $book->authors()->attach([$author1->id, $author2->id]);
    get(route('books.index'))
        ->assertElementExists(function(AssertElement $element) use($author1, $author2) {
            $element->contains('a', ['href' => Str::replace('%2C', ',', route('books.index', ['authors' => $author1->id . ',' . $author2->id]))]);
        });
});

it('has a link to filter on published year', function () {
    $this->seed(MediaTypeSeeder::class);
    $book = Book::factory()->create();
    get(route('books.index'))
        ->assertElementExists(function(AssertElement $element) use($book) {
            $element
                ->contains('a', ['href' => route('books.index', ['published' => $book->published_year])]);
        });
});

it('has a link to filter on genre', function () {
    $this->seed(MediaTypeSeeder::class);
    $book = Book::factory()->create();
    get(route('books.index'))
        ->assertElementExists(function(AssertElement $element) use($book) {
            $element
                ->contains('a', ['href' => route('books.index', ['genre' => $book->genre->name])]);
        });
});

it('has a link to filter on format', function () {
    $this->seed(MediaTypeSeeder::class);
    $book = Book::factory()->create();
    get(route('books.index'))
        ->assertElementExists(function(AssertElement $element) use($book) {
            $element
                ->contains('a', ['href' => route('books.index', ['format' => $book->format->name])]);
        });
});

it('filters on the author if the query string contains an author id', function () {
    $this->seed(MediaTypeSeeder::class);
    $bookToSee = Book::factory()->create();
    $bookNotToSee = Book::factory()->create();
    $authorToSee = Author::factory()->create();
    $authorNotToSee = Author::factory()->create();

    $bookToSee->authors()->attach([$authorToSee->id]);
    $bookNotToSee->authors()->attach([$authorNotToSee->id]);
    get(route('books.index', ['authors' => $authorToSee->id]))
        ->assertOk()
        ->assertSeeText([$bookToSee->title])
        ->assertDontSeeText([$bookNotToSee->title]);
});

it('filters on the authors if the query string contains more than one author id', function () {
    $this->seed(MediaTypeSeeder::class);
    $bookToSee1 = Book::factory()->create();
    $bookToSee2 = Book::factory()->create();
    $bookNotToSee = Book::factory()->create();
    $authorToSee1 = Author::factory()->create();
    $authorToSee2 = Author::factory()->create();
    $authorNotToSee = Author::factory()->create();

    $bookToSee1->authors()->attach([$authorToSee1->id]);
    $bookToSee2->authors()->attach([$authorToSee2->id]);
    $bookNotToSee->authors()->attach([$authorNotToSee->id]);
    $route = Str::replace('%2C', ',', route('books.index',
        ['authors' => $authorToSee1->id . ',' . $authorToSee2->id]));
    get($route)
        ->assertOk()
        ->assertSeeText([$bookToSee1->title, $bookToSee2->title])
        ->assertDontSeeText([$bookNotToSee->title]);
});

it('filters on the published year if the query string contains a year', function () {
    $this->seed(MediaTypeSeeder::class);
    $bookToSee1 = Book::factory()->create(['published_year' => 2002]);
    $bookNotToSee = Book::factory()->create(['published_year' => 2001]);

    get(route('books.index', ['published' => 2002]))
        ->assertOk()
        ->assertSeeText([$bookToSee1->title])
        ->assertDontSeeText([$bookNotToSee->title]);
});

it('filters on the genre if the query string contains a genre', function () {
    $this->seed(MediaTypeSeeder::class);
    $bookMediaId = MediaType::where('name', 'book')->value('id');
    $genreToSee = Genre::factory()->create(['media_type_id' => $bookMediaId]);
    $genreNotToSee = Genre::factory()->create(['media_type_id' => $bookMediaId]);
    $bookToSee1 = Book::factory()->create(['genre_id' => $genreToSee->id]);
    $bookNotToSee = Book::factory()->create(['genre_id' => $genreNotToSee->id]);

    get(route('books.index', ['genre' => $genreToSee->name]))
        ->assertOk()
        ->assertSeeText([$bookToSee1->title])
        ->assertDontSeeText([$bookNotToSee->title]);
});

it('filters on the format if the query string contains a format', function () {
    $this->seed(MediaTypeSeeder::class);
    $bookMediaId = MediaType::where('name', 'book')->value('id');
    $formatToSee = Format::factory()->create(['media_type_id' => $bookMediaId]);
    $formatNotToSee = Format::factory()->create(['media_type_id' => $bookMediaId]);
    $bookToSee1 = Book::factory()->create(['format_id' => $formatToSee->id]);
    $bookNotToSee = Book::factory()->create(['format_id' => $formatNotToSee->id]);

    get(route('books.index', ['format' => $formatToSee->name]))
        ->assertOk()
        ->assertSeeText([$bookToSee1->title])
        ->assertDontSeeText([$bookNotToSee->title]);
});

it('has a link to reset any filters applied', function () {
    get(route('books.index'))
        ->assertOk()
        ->assertElementExists(function(AssertElement $element) {
            $element
                ->contains('main > a', [
                    'href' => route('books.index'),
                    'text' => 'Show All',
                ]);
        });
});

it('filters on the title when the query string contains a search term', function () {
    $this->seed(MediaTypeSeeder::class);
    $bookToSee = Book::factory()->create();
    $bookNotToSee = Book::factory()->create();

    get(route('books.index', ['search' => $bookToSee->title]))
        ->assertOk()
        ->assertSeeText([$bookToSee->title])
        ->assertDontSeeText([$bookNotToSee->title]);
});

it('filters on the author when the query string contains a search term', function () {
    $this->seed(MediaTypeSeeder::class);
    $bookToSee = Book::factory()->create();
    $bookNotToSee = Book::factory()->create();
    $authorToSee = Author::factory()->create();
    $authorNotToSee = Author::factory()->create();

    $bookToSee->authors()->attach([$authorToSee->id]);
    $bookNotToSee->authors()->attach([$authorNotToSee->id]);
    get(route('books.index', ['search' => $authorToSee->last_name . ', ' . $authorToSee->first_name]))
        ->assertOk()
        ->assertSeeText([$bookToSee->title])
        ->assertDontSeeText([$bookNotToSee->title]);
});

it('filters on the series when the query string contains a search term', function () {
    $this->seed(MediaTypeSeeder::class);
    $bookToSee = Book::factory()->create();
    $bookNotToSee = Book::factory()->create();
    get(route('books.index', ['search' => $bookToSee->series->name]))
        ->assertOk()
        ->assertSeeText([$bookToSee->title])
        ->assertDontSeeText([$bookNotToSee->title]);
});

it('filters on partial titles', function () {
    $this->seed(MediaTypeSeeder::class);
    $bookToSee = Book::factory()->create(['title' => 'The Dragon Reborn']);
    $bookNotToSee = Book::factory()->create(['title' => 'Pawn Of Prophecy']);

    get(route('books.index', ['search' => 'Drag']))
        ->assertOk()
        ->assertSeeText([$bookToSee->title])
        ->assertDontSeeText([$bookNotToSee->title]);
});

it('has case insensitive search', function () {
    $this->seed(MediaTypeSeeder::class);
    $bookToSee = Book::factory()->create(['title' => 'The Dragon Reborn']);
    $bookNotToSee = Book::factory()->create(['title' => 'Pawn Of Prophecy']);

    get(route('books.index', ['search' => 'tHe DragOn rebOrn']))
        ->assertOk()
        ->assertSeeText([$bookToSee->title])
        ->assertDontSeeText([$bookNotToSee->title]);
});

it('filters on partial authors', function () {
    $this->seed(MediaTypeSeeder::class);
    $bookToSee = Book::factory()->create();
    $bookNotToSee = Book::factory()->create();
    $authorToSee = Author::factory()->create(['first_name' => 'Robert', 'last_name' => 'Jordan']);
    $authorNotToSee = Author::factory()->create();

    $bookToSee->authors()->attach([$authorToSee->id]);
    $bookNotToSee->authors()->attach([$authorNotToSee->id]);
    get(route('books.index', ['search' => $authorToSee->first_name]))
        ->assertOk()
        ->assertSeeText([$bookToSee->title])
        ->assertDontSeeText([$bookNotToSee->title]);
});

it('filters on partial series', function () {
    $this->seed(MediaTypeSeeder::class);
    $bookToSee = Book::factory()->create(['series_id' => Series::factory()->create(['name' =>'The Wheel Of Time'])]);
    $bookNotToSee = Book::factory()->create();
    get(route('books.index', ['search' => 'Wheel']))
        ->assertOk()
        ->assertSeeText([$bookToSee->title])
        ->assertDontSeeText([$bookNotToSee->title]);
});
