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
        Schema::create('events', function (Blueprint $table) {
            $table->id('event_id');
            $table->unsignedBigInteger('sport_id');
            $table->string('event_name')->index();
            $table->date('event_date');
            $table->string('location')->nullable();
            $table->enum('event_type', ['Training','Competition','Meeting', 'TryOut']);
            $table->integer('removed')->default(0)->index();
            $table->timestamps();
            $table->foreign('sport_id')->references('sport_id')->on('sports')->onDelete('cascade');
            $table->index('sport_id');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
