<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookIndexViews extends Migration
{
    public function up(): void
    {

        DB::statement("CREATE OR REPLACE VIEW book_index_views AS
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
                    b.id book_id,
                    b.title,
                    b.part,
                    b.published_year,
                    CASE s.name
                        WHEN 'Standalone'
                        THEN b.published_year
                        ELSE (SELECT MIN(bi.published_year)
                              FROM books bi
                              WHERE bi.series_id = b.series_id)
                        END series_started,
                    f.name format,
                    g.name genre,
                    s.name series
                    FROM books b,
                         formats f,
                         genres g,
                         series s
                    WHERE b.genre_id = g.id
                    AND b.format_id = f.id
                    AND b.series_id = s.id
        ");
    }

    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS book_index_views');
    }
}
