<?php

use App\Models\MediaType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormatsTable extends Migration
{
    public function up(): void
    {
        Schema::create('formats', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignIdFor(MediaType::class);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('formats');
    }
}
