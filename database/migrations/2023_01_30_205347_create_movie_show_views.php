<?php

use Illuminate\Database\Migrations\Migration;

class CreateMovieShowViews extends Migration
{
    public function up(): void
    {
        DB::statement('CREATE OR REPLACE VIEW movie_show_views AS
            SELECT m.id id,
                   m.title,
                   m.release_year,
                   m.blurb,
                   m.runtime,
                   g.name genre,
                   f.name format
            FROM movies m,
                 genres g,
                 formats f
            WHERE m.genre_id = g.id
            AND m.format_id = f.id;
        ');
    }

    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS movie_show_views;');
    }
}
