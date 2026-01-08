<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teams', function (Blueprint $table) {
            $table->id('team_id');
            $table->string('team_name')->index();
            $table->unsignedBigInteger('sport_id')->nullable()->index();
            $table->timestamps();
            $table->integer('removed')->default(0);

            $table->foreign('sport_id')->references('sport_id')->on('sports')->onDelete('set null');
        });

        Schema::create('athlete_team', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('athlete_id')->index();
            $table->unsignedBigInteger('team_id')->index();
            $table->timestamps();

            $table->foreign('athlete_id')->references('athlete_id')->on('athletes')->onDelete('cascade');
            $table->foreign('team_id')->references('team_id')->on('teams')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('athlete_team');
        Schema::dropIfExists('teams');
    }
};
