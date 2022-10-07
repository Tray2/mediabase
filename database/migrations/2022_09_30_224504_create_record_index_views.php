<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function up(): void
    {
        DB::statement("CREATE OR REPLACE VIEW record_index_views AS
            SELECT a.name AS artist,
                   r.title,
                   r.release_year,
                   g.name AS genre_name,
                   f.name AS format_name
            FROM records r,
                 artists a,
                 genres g,
                 formats f
            WHERE r.artist_id = a.id
            AND r.genre_id = g.id
            AND r.format_id = f.id;
        ");
    }

    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS record_index_view');
    }
};
