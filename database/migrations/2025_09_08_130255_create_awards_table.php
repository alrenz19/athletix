<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('awards', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->unsignedBigInteger('athlete_id');
            $table->unsignedBigInteger('event_id');
            $table->string('title'); // e.g., Gold, Silver, Bronze
            $table->text('description')->nullable(); // optional description
            $table->timestamps();
            $table->foreign('athlete_id')->references('athlete_id')->on('athletes')->onDelete('cascade');
            $table->foreign('event_id')->references('event_id')->on('events')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('awards');
    }
};

