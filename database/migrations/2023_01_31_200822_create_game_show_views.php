<?php

use Illuminate\Database\Migrations\Migration;

class CreateGameShowViews extends Migration
{
    public function up(): void
    {
        DB::statement("CREATE OR REPLACE VIEW game_show_views AS
            SELECT m.id id,
                   m.title,
                   m.release_year,
                   m.blurb,
                   g.name genre,
                   f.name format,
                   p.name platform
            FROM games m,
                 genres g,
                 formats f,
                 platforms p
            WHERE m.genre_id = g.id
            AND m.format_id = f.id
            AND m.platform_id = p.id;
        ");
    }

    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS game_show_views;');
    }
}
