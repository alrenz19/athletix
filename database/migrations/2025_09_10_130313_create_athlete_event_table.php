<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('athlete_event', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('athlete_id');
            $table->unsignedBigInteger('event_id');
            $table->timestamps();

            $table->index('athlete_id');
            $table->index('event_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('athlete_event');
    }
};
