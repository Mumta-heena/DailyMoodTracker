<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Mood;
use App\Models\User;
use Carbon\Carbon;

class MoodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Find the demo user created by UserSeeder using the phone_number
        $user = User::where('phone_number', '1234567890')->first();

        // Only run if the user exists
        if ($user) {
            $moodTypes = [
                'Happy', 'Cheerful', 'Excited', 'Calm', 'Neutral',
                'Anxious', 'Sad', 'Angry', 'Depressed'
            ];

            $startDate = Carbon::parse('2025-06-26');
            $endDate = Carbon::now();

            $currentDate = $startDate->copy();

            while ($currentDate->lte($endDate)) {
                // Pick a random mood type
                $randomMood = $moodTypes[array_rand($moodTypes)];

                // Ensure mood for this date doesn't already exist
                Mood::firstOrCreate(
                    [
                        'user_id' => $user->id,
                        'entry_date' => $currentDate->format('Y-m-d')
                    ],
                    [
                        'mood_type' => $randomMood,
                        'note' => "Feeling {$randomMood} on {$currentDate->format('M d, Y')}."
                    ]
                );

                $currentDate->addDay();
            }
        }
    }
}