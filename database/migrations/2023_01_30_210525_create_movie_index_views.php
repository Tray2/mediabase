<?php

use Illuminate\Database\Migrations\Migration;

class CreateMovieIndexViews extends Migration
{
    public function up(): void
    {
        DB::statement('CREATE OR REPLACE VIEW movie_index_views AS
            SELECT m.title,
                   m.release_year,
                   m.runtime,
                   f.name format,
                   g.name genre
            FROM movies m,
                 genres g,
                 formats f
            WHERE m.format_id = f.id
            AND m.genre_id = g.id
        ');
    }

    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS movie_index_views;');
    }
}
