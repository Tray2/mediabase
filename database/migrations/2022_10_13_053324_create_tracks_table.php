<?php

use App\Models\Artist;
use App\Models\Record;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tracks', function (Blueprint $table) {
            $table->id();
            $table->string('position');
            $table->foreignIdFor(Artist::class)->nullable();
            $table->string('title');
            $table->string('duration');
            $table->string('mix')->nullable();
            $table->foreignIdFor(Record::class);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tracks');
    }
};
