<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Athlete;
use App\Models\Award;
use App\Models\Event;

class AwardSeeder extends Seeder
{
    public function run(): void
    {
        $thresholds = [
            'Gold' => 90,
            'Silver' => 75,
            'Bronze' => 60,
        ];

        $athletes = Athlete::all();
        $event = Event::first();
        $eventId = $event ? $event->event_id : 1;

        foreach ($athletes as $athlete) {
            // Use the relationship as a query to avoid null
            $totalScore = $athlete->performances()->sum('score');
            $count = $athlete->performances()->count();
            $averageScore = $count ? $totalScore / $count : 0;

            $awardTitle = null;

            if ($averageScore >= $thresholds['Gold']) {
                $awardTitle = 'Gold';
            } elseif ($averageScore >= $thresholds['Silver']) {
                $awardTitle = 'Silver';
            } elseif ($averageScore >= $thresholds['Bronze']) {
                $awardTitle = 'Bronze';
            }

            if ($awardTitle) {
                Award::create([
                    'athlete_id'  => $athlete->athlete_id,
                    'event_id'    => $eventId,
                    'title'       => $awardTitle,
                    'description' => "{$awardTitle} Award for excellent performance",
                ]);
            }
        }


        $this->command->info('Awards seeded based on athlete performance!');
    }
}
