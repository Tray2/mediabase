<?php

use App\Models\Artist;
use App\Models\Format;
use App\Models\Genre;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('records', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->integer('released');
            $table->foreignIdFor(Artist::class);
            $table->foreignIdFor(Genre::class);
            $table->foreignIdFor(Format::class);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('records');
    }
};
