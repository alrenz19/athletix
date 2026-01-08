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
    Schema::create('sections', function (Blueprint $table) {
        $table->id('section_id');
        $table->string('section_name')->index();
        $table->unsignedBigInteger('course_id');
        $table->integer('removed')->default(0)->index();
        $table->timestamps();

        $table->foreign('course_id')->references('course_id')->on('courses')->onDelete('cascade');
        $table->index('course_id');
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sections');
    }
};
