<?php

use App\Models\Artist;
use App\Models\Country;
use App\Models\Format;
use App\Models\Genre;
use App\Models\RecordLabel;
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
            $table->string('barcode');
            $table->string('spine_code');
            $table->foreignIdFor(Artist::class);
            $table->foreignIdFor(Genre::class);
            $table->foreignIdFor(Format::class);
            $table->foreignIdFor(RecordLabel::class);
            $table->foreignIdFor(Country::class);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('records');
    }
};
