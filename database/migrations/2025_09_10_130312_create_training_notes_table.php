<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('training_notes', function (Blueprint $table) {
            $table->id('note_id');
            $table->unsignedBigInteger('coach_id');
            $table->unsignedBigInteger('athlete_id');
            $table->text('note');
            $table->timestamps();

            $table->index('coach_id');
            $table->index('athlete_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('training_notes');
    }
};
