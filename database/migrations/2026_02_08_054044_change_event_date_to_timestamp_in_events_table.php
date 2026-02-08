<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            // Add new timestamp column
            $table->timestamp('event_date_timestamp')->nullable()->after('event_date');
        });

        // Copy and convert existing date data to timestamp
        DB::statement('UPDATE events SET event_date_timestamp = CAST(event_date AS DATETIME)');

        // Drop the old date column
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('event_date');
        });

        // Rename the new column to original name
        Schema::table('events', function (Blueprint $table) {
            $table->renameColumn('event_date_timestamp', 'event_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            // Add back date column
            $table->date('event_date_date')->nullable()->after('event_date');
        });

        // Copy data back (losing time information)
        DB::statement('UPDATE events SET event_date_date = DATE(event_date)');

        // Drop the timestamp column
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('event_date');
        });

        // Rename back to original
        Schema::table('events', function (Blueprint $table) {
            $table->renameColumn('event_date_date', 'event_date');
        });
    }
};