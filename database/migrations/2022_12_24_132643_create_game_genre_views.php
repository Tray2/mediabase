<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateGameGenreViews extends Migration
{
    public function up(): void
    {
        DB::statement("CREATE OR REPLACE VIEW game_genre_views AS
           SELECT g.name
           FROM genres g,
                media_types mt
           WHERE g.media_type_id = mt.id
           AND mt.name = 'game';
        ");
    }

    public function down(): void
    {
        Schema::dropIfExists('game_genre_views');
    }
}
