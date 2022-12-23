<?php

use App\Models\Actor;
use App\Models\Movie;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActorMovieTable extends Migration
{
    public function up(): void
    {
        Schema::create('actor_movie', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Actor::class);
            $table->foreignIdFor(Movie::class);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('actor_movie');
    }
}
