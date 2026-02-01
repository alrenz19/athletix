<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/xxxx_add_date_to_attendances_table.php
    public function up()
    {
        Schema::table('attendance', function (Blueprint $table) {
            $table->date('event_date')->nullable()-after('status');
            $table->date('date')->nullable()->after('event_date');
        });
    }

    public function down()
    {
        Schema::table('attendance', function (Blueprint $table) {
            $table->dropColumn('date');
            $table->dropColumn('event_date');
        });
    }
};
