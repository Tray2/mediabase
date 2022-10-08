<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::statement("CREATE OR REPLACE VIEW book_format_views AS
           SELECT f.name
           FROM formats f,
                media_types mt
           WHERE f.media_type_id = mt.id
           AND mt.name = 'book';
        ");
    }

    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS book_format_views');
    }
};
