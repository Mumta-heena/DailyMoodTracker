<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UpdateMoodRequest;
use App\Models\Mood;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class MoodController extends Controller
{
    /**
     * Display the main dashboard with summary data and charts.
     */
    public function dashboard(): View
    {
        $user = auth()->user();

        // --- Data for Monthly Mood Summary (Last 30 Days) ---
        $thirtyDaysAgo = Carbon::now()->subDays(30)->startOfDay();
        $moodsLast30Days = $user->moods()
                                ->where('entry_date', '>=', $thirtyDaysAgo)
                                ->selectRaw('mood_type, count(*) as count')
                                ->groupBy('mood_type')
                                ->orderByDesc('count')
                                ->get();

        // Prepare data for the 30-day Chart.js
        $chartLabels = $moodsLast30Days->pluck('mood_type');
        $chartData = $moodsLast30Days->pluck('count');

        // Get the most frequent mood to display for the 30-day summary
        $mostFrequentMood = $moodsLast30Days->first();


        // --- Data for Weekly Mood Tracker Chart (Last 7 Days) ---
        // Define a numerical scale for all mood types, including the new ones.
        // The order here should match the 'moodLabels' array in dashboard.blade.php's script.
        $moodScale = [
            'Angry'     => 0,
            'Sad'       => 1,
            'Anxious'   => 2,
            'Calm'      => 3,
            'Excited'   => 4,
            'Happy'     => 5,
            'Cheerful'  => 6,
            'Neutral'   => 7,
            'Depressed' => 8,
        ];

        $weeklyDates = [];
        $weeklyMoodData = [];
        $today = Carbon::now();

        // Loop through the last 7 days (including today) to prepare the data for the weekly chart
        for ($i = 6; $i >= 0; $i--) {
            $date = $today->copy()->subDays($i)->startOfDay();
            $weeklyDates[] = $date->format('M d'); // Format the date for the chart label (e.g., 'Jul 04')

            // Find a mood for the specific day for the current user
            $moodForDay = $user->moods()->whereDate('entry_date', $date)->first();
            
            // If a mood exists, get its numerical value from the scale. Otherwise, use null to create a gap in the bar chart.
            $weeklyMoodData[] = $moodForDay ? $moodScale[$moodForDay->mood_type] : null;
        }


        // --- Data for Streak and Last Logged Mood ---
        // Calculate the user's current consecutive mood logging streak
        $streakCount = $user->calculateStreak(); // Assuming calculateStreak() is a method on your User model

        // Get the most recently logged mood
        $lastMood = $user->moods()->latest('entry_date')->first();


        // --- Data for Recent Moods Table ---
        // Get the latest 5 mood entries for the recent moods section on the dashboard
        $recentMoods = $user->moods()->latest('entry_date')->take(5)->get();


        // Pass all the necessary data to the dashboard view
        return view('dashboard', compact(
            'streakCount',
            'lastMood',
            'mostFrequentMood',
            'chartLabels',
            'chartData',
            'weeklyDates',
            'weeklyMoodData',
            'recentMoods'
        ));
    }


    /**
     * Display a listing of the resource (the full mood history page).
     */
    public function index(Request $request): View
    {
        $user = auth()->user();

        // Get all moods for the logged-in user, ordered from latest to first
        $moods = $user->moods()->latest();

        // Apply date range filtering if the dates are provided in the request
        if ($request->filled('from_date') && $request->filled('to_date')) {
            $moods->whereBetween('entry_date', [$request->from_date, $request->to_date]);
        }

        // Execute the query to get the filtered moods
        $moods = $moods->get();

        // Calculate the user's current consecutive mood logging streak for the history page badge
        $streakCount = $user->calculateStreak();

        // Pass only the data needed for the history page
        return view('moods.history', compact('moods', 'streakCount'));
    }


    public function exportPdf(Request $request)
    {
        $user = auth()->user();
        $moods = $user->moods()->latest();

        // Apply date range filtering
        if ($request->filled('from_date') && $request->filled('to_date')) {
            $moods->whereBetween('entry_date', [$request->from_date, $request->to_date]);
        }

        $moods = $moods->get();

        // Load a Blade view to be converted to PDF
        $pdf = Pdf::loadView('pdfs.mood_log', ['moods' => $moods]);

        // Return the generated PDF as a download
        return $pdf->download('mood-log-' . now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('moods.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'mood_type' => ['required', 'string'],
            'note' => ['nullable', 'string', 'max:255'],
            'entry_date' => ['required', 'date'],
        ]);

        $entryDate = \Carbon\Carbon::parse($request->entry_date)->toDateString();

        // This check is duplicated with the client-side check, but is a crucial server-side validation.
        if (auth()->user()->moods()->whereDate('entry_date', $entryDate)->exists()) {
            return redirect()->back()->with('error', 'You have already logged a mood for the selected date.');
        }

        auth()->user()->moods()->create([
            'mood_type' => $request->mood_type,
            'note' => $request->note,
            'entry_date' => $entryDate,
        ]);

        return redirect()->route('moods.index')->with('success', 'Mood entry added successfully!');
    }

    /**
     * API endpoint to check if a mood already exists for a given date for the authenticated user.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkMoodExists(Request $request)
    {
        $request->validate([
            'entry_date' => 'required|date_format:Y-m-d',
        ]);

        $date = $request->input('entry_date');
        $user = Auth::user();

        $exists = $user->moods()->whereDate('entry_date', $date)->exists();

        return response()->json(['exists' => $exists]);
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Not implemented for this application's current features
    }

    /**
     * Show the form for editing the specified mood entry.
     */
    public function edit(Mood $mood): View
    {
        return view('moods.edit', compact('mood'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMoodRequest $request, Mood $mood): RedirectResponse
    {
        $mood->update($request->validated());
        return redirect()->route('moods.index')->with('success', 'Mood entry updated successfully!');
    }

    public function summary(): View
    {
        // This method will likely become redundant as dashboard now has charts
        // but keeping it for now if there are external links to it.
        $startOfWeek = Carbon::now()->startOfWeek(Carbon::MONDAY)->toDateString();
        $endOfWeek = Carbon::now()->endOfWeek(Carbon::SUNDAY)->toDateString();

        $moodTypes = ['Happy', 'Sad', 'Angry', 'Excited', 'Calm', 'Anxious', 'Cheerful', 'Neutral', 'Depressed'];

        $weeklyMoods = auth()->user()->moods()
                                ->select('mood_type')
                                ->selectRaw('count(*) as total')
                                ->whereBetween('entry_date', [$startOfWeek, $endOfWeek])
                                ->groupBy('mood_type')
                                ->pluck('total', 'mood_type')
                                ->toArray();

        $chartData = array_fill_keys($moodTypes, 0);

        foreach ($weeklyMoods as $moodType => $count) {
            if (in_array($moodType, $moodTypes)) {
                $chartData[$moodType] = $count;
            }
        }

        $chartLabels = array_keys($chartData);
        $chartValues = array_values($chartData);

        return view('moods.summary', compact('chartLabels', 'chartValues'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): RedirectResponse
    {
        Mood::find($id)->delete();
        return redirect()->route('moods.index')->with('success', 'Mood entry soft deleted!');
    }

    public function trashed(): View
    {
        $trashedMoods = auth()->user()->moods()->onlyTrashed()->get();
        return view('moods.trashed', compact('trashedMoods'));
    }

    public function restore($id): RedirectResponse
    {
        Mood::onlyTrashed()->findOrFail($id)->restore();
        return redirect()->route('moods.index')->with('status', 'Mood entry restored successfully!');
    }
}
