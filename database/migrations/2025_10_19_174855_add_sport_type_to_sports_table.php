<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('sports', function (Blueprint $table) {
            $table->enum('sport_type', ['varsity', 'non_varsity'])->default('varsity')->after('sport_name');
        });
    }

    public function down()
    {
        Schema::table('sports', function (Blueprint $table) {
            $table->dropColumn('sport_type');
        });
    }
};