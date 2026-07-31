<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('movies', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('poster')->nullable();
            $table->text('synopsis');
            $table->string('genre');
            $table->unsignedInteger('duration'); // dalam menit
            $table->string('rating', 10)->nullable();
            $table->string('trailer')->nullable(); // URL YouTube
            $table->enum('status', ['now_showing', 'coming_soon'])->default('coming_soon');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movies');
    }
};
