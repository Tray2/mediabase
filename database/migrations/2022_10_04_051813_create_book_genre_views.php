<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("CREATE OR REPLACE VIEW book_genre_views AS
           SELECT g.name
           FROM genres g,
                media_types mt
           WHERE g.media_type_id = mt.id
           AND mt.name = 'book';
        ");
    }

    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS book_genre_views');
    }
};
