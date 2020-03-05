<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('series');
            $table->unsignedInteger('part')->nullable();
            $table->unsignedBigInteger('format_id');
            $table->unsignedBigInteger('genre_id');
            $table->string('isbn');
            $table->unsignedInteger('released');
            $table->unsignedInteger('reprinted')->nullable();
            $table->unsignedInteger('pages');
            $table->text('blurb');
            $table->timestamps();

            //$table->foreign('author_id')->references('id')->on('authors');
            //$table->foreign('format_id')->references('id')->on('formats');
            //$table->foreign('genre_id')->references('id')->on('genres');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('books');
    }
}
