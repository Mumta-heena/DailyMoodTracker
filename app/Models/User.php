<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Mood;
use Illuminate\Support\Carbon;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'phone_number',
        'password',
    ];

    public function moods(): HasMany
    {
        // A user has many moods
        return $this->hasMany(Mood::class);
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
public function getMoodStreakAttribute(): int
{
    // Get the user's mood entries, ordered by the most recent date
    $moods = $this->moods()->orderBy('entry_date', 'desc')->get();

    // If there are no moods, the streak is 0
    if ($moods->isEmpty()) {
        return 0;
    }

    $streak = 0;
    $previousDate = null;

    foreach ($moods as $mood) {
        $currentDate = Carbon::parse($mood->entry_date);

        // Check if the current entry date is one day before the previous entry date
        // and if it's not the first entry in the loop
        if ($previousDate && $currentDate->diffInDays($previousDate) == 1) {
            $streak++;
        } else {
            // If the difference is not 1 day, reset the streak to 1
            // This is crucial to start a new streak when there is a gap
            $streak = 1;
        }

        // Update the previous date for the next iteration
        $previousDate = $currentDate;
    }

    return $streak;
}

public function calculateStreak(): int
{
    // Get unique, distinct dates of logged moods, sorted in descending order
    $moodDates = $this->moods()
                        ->selectRaw('DATE(entry_date) as date_only')
                        ->distinct()
                        ->orderBy('date_only', 'desc')
                        ->pluck('date_only')
                        ->map(fn($date) => \Carbon\Carbon::parse($date)->startOfDay());

    if ($moodDates->isEmpty()) {
        return 0;
    }

    $latestLoggedDate = $moodDates->first();
    if ($latestLoggedDate->isBefore(\Carbon\Carbon::yesterday()->startOfDay())) {
        return 0;
    }

    $streak = 0;
    $lastDate = null;

    // Iterate through the dates to find the consecutive sequence
    foreach ($moodDates as $date) {
        if ($lastDate === null) {
            $streak = 1;
        } elseif ($date->diffInDays($lastDate) == 1) { // Changed === to ==
            $streak++;
        } else {
            // The loop will now break only if there is a true gap
            break;
        }
        $lastDate = $date;
    }
    
    return $streak;
}


}
