<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        DB::statement("CREATE OR REPLACE VIEW record_show_views AS
            SELECT r.*,
                   a.name AS artist,
                   g.name AS genre,
                   f.name AS format,
                   rl.name AS record_label,
                   c.name AS country
            FROM artists a,
                 records r,
                 formats f,
                 genres g,
                 record_labels rl,
                 countries c
            WHERE r.artist_id = a.id
            AND r.genre_id = g.id
            AND r.format_id = f.id
            AND r.record_label_id = rl.id
            AND r.country_id = c.id;"
        );
    }

    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS record_show_views');
    }
};
