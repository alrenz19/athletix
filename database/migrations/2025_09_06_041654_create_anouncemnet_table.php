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
        Schema::create('announcements', function (Blueprint $table) {
            $table->id('announcement_id');
            $table->string('title', 255)->index();
            $table->text('message');
            $table->unsignedBigInteger('posted_by');
            $table->enum('target', ['All', 'Athletes', 'Coaches', 'Staff'])->default('All');
            $table->unsignedBigInteger('sport_id')->nullable();
            $table->unsignedBigInteger('section_id')->nullable();
            $table->integer('removed')->default(0)->index();
            $table->timestamps();

            $table->foreign('posted_by')->references('user_id')->on('users')->onDelete('cascade');
            $table->foreign('sport_id')->references('sport_id')->on('sports')->onDelete('set null');
            $table->foreign('section_id')->references('section_id')->on('sections')->onDelete('set null');

            $table->index('posted_by');
            $table->index('sport_id');
            $table->index('section_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};
