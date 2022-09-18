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
                    b.series,
                    b.part,
                    b.published_year,
                    CASE series
                        WHEN 'Standalone'
                        THEN b.published_year
                        ELSE (SELECT MIN(bi.published_year)
                              FROM books bi
                              WHERE bi.series = b.series)
                        END series_started
                FROM books b
        ");
    }

    public function down(): void
    {
        DB::statement('DROP INDEX IF EXISTS book_index_views');
    }
}
