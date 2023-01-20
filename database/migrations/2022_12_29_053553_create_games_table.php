<?php

use App\Models\Format;
use App\Models\Genre;
use App\Models\Platform;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGamesTable extends Migration
{
    public function up(): void
    {
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->integer('release_year');
            $table->foreignIdFor(Format::class);
            $table->foreignIdFor(Genre::class);
            $table->foreignIdFor(Platform::class);
            $table->text('blurb');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('games');
    }
}
