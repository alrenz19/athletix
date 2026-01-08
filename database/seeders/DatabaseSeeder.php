<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Call the CTU Seeder
        $this->call([
            CTUSeeder::class,
        ]);
        $this->call([
            AwardSeeder::class,
        ]);
    }
}
