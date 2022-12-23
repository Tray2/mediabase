<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('actors', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('full_name')->virtualAs("CONCAT(first_name, last_name, ' ')");
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('actors');
    }
};
