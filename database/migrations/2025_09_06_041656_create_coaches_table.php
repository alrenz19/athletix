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
        Schema::create('coaches', function (Blueprint $table) {
            $table->id('coach_id');
            $table->unsignedBigInteger('user_id');
            $table->string('full_name')->index();
            $table->string('specialization')->nullable();
            $table->unsignedBigInteger('sport_id')->nullable();
            $table->integer('removed')->default(0)->index();
            $table->timestamps();

            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('sport_id')->references('sport_id')->on('sports')->onDelete('set null');

            $table->index('user_id');
            $table->index('sport_id');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coaches');
    }
};
