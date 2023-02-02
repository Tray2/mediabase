<?php

use Illuminate\Database\Migrations\Migration;

class CreateGameIndexViews extends Migration
{
    public function up(): void
    {
        DB::statement('CREATE OR REPLACE VIEW game_index_views AS
            SELECT m.title,
                   m.release_year,
                   f.name format,
                   g.name genre,
                   p.name platform
            FROM games m,
                 genres g,
                 formats f,
                 platforms p
            WHERE m.format_id = f.id
            AND m.genre_id = g.id
            AND m.platform_id = p.id
        ');
    }

    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS game_index_views;');
    }
}
