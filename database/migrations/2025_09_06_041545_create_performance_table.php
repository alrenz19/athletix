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
        Schema::create('performance', function (Blueprint $table) {
            $table->id('performance_id');
            $table->unsignedBigInteger('athlete_id');
            $table->unsignedBigInteger('event_id');
            $table->decimal('score', 5, 2)->nullable();
            $table->text('remarks')->nullable();
            $table->integer('removed')->default(0)->index();
            $table->timestamps();

            $table->foreign('athlete_id')->references('athlete_id')->on('athletes')->onDelete('cascade');
            $table->foreign('event_id')->references('event_id')->on('events')->onDelete('cascade');

            $table->index('athlete_id');
            $table->index('event_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('performance');
    }
};
