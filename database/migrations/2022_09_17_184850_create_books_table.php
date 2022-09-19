<?php

use App\Models\Format;
use App\Models\Genre;
use App\Models\Series;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->integer('published_year');
            $table->foreignIdFor(Series::class);
            $table->tinyInteger('part')->nullable();
            $table->foreignIdFor(Format::class);
            $table->foreignIdFor(Genre::class);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
