<?php

use App\Models\Author;
use App\Models\Book;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('author_book', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Author::class);
            $table->foreignIdFor(Book::class);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('author_book');
    }
};
