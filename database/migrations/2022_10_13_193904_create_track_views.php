<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function up(): void
    {
        DB::statement("CREATE OR REPLACE VIEW track_views AS
            SELECT t.record_id,
                   t.position,
                   t.title,
                   t.duration,
                   t.mix,
                   a.name AS artist
            FROM tracks AS t
            LEFT OUTER JOIN artists AS a
            ON t.artist_id = a.id;");
    }

    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS track_views');
    }
};
