<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecordViews extends Migration
{
    public function up()
    {
        if (App::environment() == 'local') {
            DB::statement(
                "CREATE OR REPLACE VIEW record_views AS
            SELECT a.id artist_id,
                   a.name,
                   r.id record_id,
                   r.title,
                   r.released,
                   g.id genre_id,
                   g.genre,
                   f.id format_id,
                   f.format,
                   (SELECT ROUND(AVG(score), 1) FROM scores WHERE media_type_id = 4 AND item_id = r.id) rating
            FROM artists a,
                 records r,
                 genres g,
                 formats f
            WHERE r.artist_id = a.id
            AND r.genre_id = g.id
            AND   r.format_id = f.id"
            );
        } else {
            DB::statement(
                "CREATE VIEW IF NOT EXISTS record_views AS
                SELECT a.id artist_id,
                   a.name,
                   r.id record_id,
                   r.title,
                   r.released,
                   g.id genre_id,
                   g.genre,
                   f.id format_id,
                   f.format,
                   (SELECT ROUND(AVG(score), 1) FROM scores WHERE media_type_id = 4 AND item_id = r.id) rating
                FROM artists a,
                   records r,
                   genres g,
                   formats f
                WHERE r.artist_id = a.id
                AND r.genre_id = g.id
                AND   r.format_id = f.id"
            );
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('DROP VIEW IF EXISTS record_views');
    }
}
