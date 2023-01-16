<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateGameFormatViews extends Migration
{
    public function up(): void
    {
        DB::statement("CREATE OR REPLACE VIEW game_format_views AS
           SELECT f.name
           FROM formats f,
                media_types mt
           WHERE f.media_type_id = mt.id
           AND mt.name = 'game';
        ");
    }

    public function down(): void
    {
        Schema::dropIfExists('game_format_views');
    }
}
