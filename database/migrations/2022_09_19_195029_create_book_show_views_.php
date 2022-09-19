<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookShowViews extends Migration
{
    public function up(): void
    {
        DB::statement("
            CREATE OR REPLACE VIEW book_show_views AS
            SELECT
                    (SELECT GROUP_CONCAT(a.id ORDER BY a.id SEPARATOR ',')
                    FROM authors a, author_book ab
                    WHERE a.id = ab.author_id
                    AND ab.book_id = b.id) author_id,
                    (SELECT GROUP_CONCAT(concat(a.last_name, ', ', a.first_name)
                    ORDER BY a.last_name, a.first_name SEPARATOR ' & ')
                    FROM authors a, author_book ab
                    WHERE ab.author_id = a.id
                    AND ab.book_id = b.id) author_name,
                    b.id id,
                    b.title,
                    b.part,
                    b.published_year,
                    b.isbn,
                    b.blurb,
                    f.name format,
                    g.name genre,
                    s.name series,
                    p.name publisher
                    FROM books b,
                         formats f,
                         genres g,
                         series s,
                         publishers p
                    WHERE b.genre_id = g.id
                    AND b.format_id = f.id
                    AND b.series_id = s.id
                    AND b.publisher_id = p.id
        ");
    }

    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS book_show_views;');
    }
}
