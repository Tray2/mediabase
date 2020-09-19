<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecordCollectionViews extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (App::environment() == 'local') {
            DB::statement(
                "CREATE OR REPLACE VIEW record_collection_views AS
            SELECT a.id artist_id,
                   a.name,
                   r.id book_id,
                   r.title,
                   r.released,
                   g.id genre_id,
                   g.genre,
                   f.id format_id,
                   f.format,
                   rc.user_id
            FROM artists a,
                 records r,
                 genres g,
                 formats f,
                 record_collections rc
            WHERE r.genre_id = g.id
            AND   r.format_id = f.id
            AND   r.id = rc.record_id"
            );
        } else {
            DB::statement(
                "CREATE VIEW IF NOT EXISTS record_collection_views AS
                SELECT a.id artist_id,
                   a.name,
                   r.id book_id,
                   r.title,
                   r.released,
                   g.id genre_id,
                   g.genre,
                   f.id format_id,
                   f.format,
                   rc.user_id
                FROM artists a,
                   records r,
                   genres g,
                   formats f,
                   record_collections rc
                WHERE r.genre_id = g.id
                AND   r.format_id = f.id
                AND   r.id = rc.record_id"
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
        DB::statement('DROP VIEW IF EXISTS record_collection_views');
    }
}
