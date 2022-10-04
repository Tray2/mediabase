<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::statement("CREATE OR REPLACE VIEW record_genre_views AS
           SELECT g.name
           FROM genres g,
                media_types mt
           WHERE g.media_type_id = mt.id
           AND mt.name = 'record';
        ");
    }

    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS record_genre_views');
    }
};
