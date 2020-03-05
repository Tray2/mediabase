<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateBookCollectionViews extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (App::environment() == 'local') {
            DB::statement(
                "CREATE OR REPLACE VIEW book_collection_views AS
                SELECT DISTINCT (SELECT GROUP_CONCAT(a.id ORDER BY a.id SEPARATOR ',')
                     FROM authors a, author_books ab 
                     WHERE a.id = ab.author_id 
                     AND ab.book_id = b.id) author_id,
                    (SELECT GROUP_CONCAT(concat(a.last_name, ', ', a.first_name)
                     ORDER BY a.last_name, a.first_name SEPARATOR ' & ') 
                     FROM authors a, author_books ab 
                     WHERE ab.author_id = a.id 
                     AND ab.book_id = b.id) author_name,
                     b.id book_id,
                     b.title,
                    (SELECT ROUND(AVG(s.score), 1) FROM scores s WHERE s.book_id = b.id) rating,
                    b.series,
                    b.part,
                    b.released,
                    g.id genre_id,
                    g.genre,
                    f.id format_id,
                    f.format,
                    CASE series
                    	WHEN 'Standalone' THEN b.released
                    	ELSE (SELECT MIN(bi.released) FROM books bi WHERE bi.series = b.series) 
                   	END series_started,
                    bc.user_id
                FROM authors a,
                    books b,
                    genres g,
                    formats f,
                    book_collections bc
                WHERE b.genre_id = g.id
                AND   b.format_id = f.id
                AND   b.id = bc.book_id"
            );
        } else {
            DB::statement(
                "CREATE VIEW book_collection_views AS
                SELECT  DISTINCT (SELECT GROUP_CONCAT(a.id) 
                        FROM authors a, author_books ab 
                        WHERE a.id = ab.author_id 
                        AND ab.book_id = b.id) author_id,
                        (SELECT GROUP_CONCAT(author_name, ' & ')
                         FROM (SELECT a.last_name || ', ' || a.first_name author_name
                               FROM authors a, author_books ab 
                               WHERE ab.author_id = a.id 
                               AND ab.book_id = b.id 
                               ORDER BY a.last_name, a.first_name)) author_name,
                    b.id book_id,
                    b.title,
                    (SELECT ROUND(AVG(s.score), 1) FROM scores s WHERE s.book_id = b.id) rating,
                    b.series,
                    b.part,
                    b.released,
                    g.id genre_id,
                    g.genre,
                    f.id format_id,
                    f.format,
                    CASE series
                    	WHEN 'Standalone' THEN b.released
                    	ELSE (SELECT MIN(bi.released) FROM books bi WHERE bi.series = b.series) 
                   	END series_started,
                    bc.user_id
                FROM authors  a,
                    books  b,
                    genres  g,
                    formats  f,
                    book_collections bc
                WHERE b.genre_id = g.id
                AND   b.format_id = f.id
                AND   b.id = bc.book_id"
            );
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('DROP VIEW IF EXISTS book_collection_views');
    }
}
