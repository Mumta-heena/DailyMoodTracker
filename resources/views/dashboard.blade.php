<x-app-layout>
    {{-- Push custom styles --}}
    @push('styles')
        <style>
            body {
                background-color: #F5F5F5; /* Primary Background */
            }
            .card-modern {
                background-color: #FFFFFF; /* Surface / Card Background */
                border-radius: 1rem; /* Slightly less rounded than welcome for content cards */
                box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05); /* Softer shadow */
                transition: all 0.3s ease-in-out;
            }
            .card-modern:hover {
                box-shadow: 0 12px 25px rgba(0, 0, 0, 0.1);
                transform: translateY(-3px);
            }
            .text-primary-accent {
                color: #A2D9CE !important; /* Primary Accent */
            }
            .text-secondary-accent {
                color: #B3E0FF !important; /* Secondary Accent */
            }
            .text-primary-dark {
                color: #333333 !important; /* Primary Text */
            }
            .text-secondary-light {
                color: #777777 !important; /* Secondary Text */
            }
            .border-light-gray {
                border-color: #E0E0E0 !important; /* Border / Divider */
            }
            .btn-accent {
                background-image: linear-gradient(to right, #A2D9CE 0%, #B3E0FF  51%, #A2D9CE  100%);
                padding: 0.75rem 2rem;
                text-align: center;
                text-transform: uppercase;
                transition: 0.5s;
                background-size: 200% auto;
                color: #333333;            
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
                border-radius: 0.5rem;
                border: none;
                font-weight: 600;
            }
            .btn-accent:hover {
                background-position: right center;
                color: #333333;
                text-decoration: none;
                transform: translateY(-2px);
            }
            .btn-outline-accent {
                background-color: transparent;
                border: 2px solid #A2D9CE;
                color: #A2D9CE;
                padding: 0.75rem 2rem;
                text-align: center;
                text-transform: uppercase;
                transition: 0.3s;
                border-radius: 0.5rem;
                font-weight: 600;
            }
            .btn-outline-accent:hover {
                background-color: #A2D9CE;
                color: #FFFFFF;
                text-decoration: none;
                transform: translateY(-2px);
            }
        </style>
    @endpush

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-primary-dark leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12" style="background-color: #F5F5F5;">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Welcome Card --}}
                <div class="p-6 card-modern text-primary-dark flex flex-col justify-between">
                    <div>
                        <h3 class="text-2xl font-bold mb-3">Welcome, {{ Auth::user()->name }}!</h3>
                        <p class="text-secondary-light mb-4">
                            You're logged in and ready to track your emotional well-being.
                            Let's see how your journey is going.
                        </p>
                    </div>
                    <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-3 mt-4">
                        <a href="{{ route('moods.create') }}" class="btn-accent text-center">
                            <i class="fas fa-plus-circle mr-2"></i> Log New Mood
                        </a>
                        <a href="{{ route('moods.index') }}" class="btn-outline-accent text-center">
                            <i class="fas fa-history mr-2"></i> View Full History
                        </a>
                    </div>
                </div>

                {{-- Streak & Last Logged Mood Card --}}
                <div class="p-6 card-modern text-primary-dark flex flex-col justify-between">
                    <div>
                        <h3 class="text-2xl font-bold mb-3">Your Streak</h3>
                        @if(isset($streakCount) && $streakCount >= 3)
                            <div class="p-3 mb-4 rounded-lg bg-light-green-gradient flex items-center shadow-md">
                                <i class="fas fa-fire text-orange-500 text-2xl mr-3"></i>
                                <span class="text-lg font-semibold text-primary-dark">
                                    Awesome! You're on a {{ $streakCount }}-day streak!
                                </span>
                            </div>
                        @else
                            <div class="p-3 mb-4 rounded-lg bg-light-yellow-gradient flex items-center shadow-md">
                                <i class="fas fa-calendar-check text-yellow-500 text-2xl mr-3"></i>
                                <span class="text-lg text-primary-dark">
                                    Log more moods to start a streak!
                                </span>
                            </div>
                        @endif

                        <h3 class="text-2xl font-bold mt-4 mb-3">Last Logged Mood</h3>
                        @if(isset($lastMood) && $lastMood)
                            <div class="p-3 rounded-lg border border-light-gray flex items-center">
                                <span class="text-xl mr-3">
                                    @switch($lastMood->mood_type)
                                        @case('Happy') 😊 @break
                                        @case('Sad') 😥 @break
                                        @case('Angry') 😠 @break
                                        @case('Excited') 🤩 @break
                                        @case('Anxious') 😰 @break
                                        @case('Calm') 😌 @break
                                        @case('Cheerful') 😄 @break
                                        @case('Neutral') 😐 @break
                                        @case('Depressed') 😔 @break
                                        @default ❓
                                    @endswitch
                                </span>
                                <span class="text-lg font-semibold text-primary-dark">
                                    {{ $lastMood->mood_type }} ({{ \Carbon\Carbon::parse($lastMood->entry_date)->format('M d, Y') }})
                                </span>
                            </div>
                            <p class="text-secondary-light mt-2">{{ $lastMood->note ?? 'No note added.' }}</p>
                        @else
                            <p class="text-secondary-light">No moods logged yet. <a href="{{ route('moods.create') }}" class="text-primary-accent hover:underline">Log your first mood!</a></p>
                        @endif
                    </div>
                </div>

                {{-- Monthly Mood Summary Chart --}}
                <div class="p-6 card-modern text-primary-dark col-span-1 md:col-span-2">
                    <h3 class="text-2xl font-bold mb-3">Monthly Mood Overview</h3>
                    <p class="text-secondary-light mb-4">Your mood distribution over the last 30 days.</p>
                    @if(isset($mostFrequentMood) && $mostFrequentMood)
                        <div class="alert alert-light border-start border-4 border-primary-accent p-3 mb-4 rounded-lg">
                            <p class="mb-0 text-primary-dark">Your most frequent mood is: <strong class="text-primary-accent">{{ $mostFrequentMood->mood_type }}</strong> ({{ $mostFrequentMood->count }} times)</p>
                        </div>
                        <div style="height: 300px;">
                            <canvas id="moodChart"></canvas>
                        </div>
                    @else
                        <div class="alert alert-info text-center" role="alert">Log more moods to see your monthly summary!</div>
                    @endif
                </div>

                {{-- Weekly Mood Tracker Chart --}}
                <div class="p-6 card-modern text-primary-dark col-span-1 md:col-span-2">
                    <h3 class="text-2xl font-bold mb-3">Weekly Mood Tracker</h3>
                    <p class="text-secondary-light mb-4">Your mood trend over the last 7 days.</p>
                    @if(count(array_filter($weeklyMoodData)) > 0)
                        <div style="height: 300px;">
                            <canvas id="weeklyMoodChart"></canvas>
                        </div>
                    @else
                        <div class="alert alert-info text-center" role="alert">Log moods to see your weekly tracker!</div>
                    @endif
                </div>

                {{-- Recent Moods Table --}}
                <div class="p-6 card-modern text-primary-dark col-span-1 md:col-span-2">
                    <h3 class="text-2xl font-bold mb-4">Recent Moods</h3>
                    @if($recentMoods->isEmpty())
                        <div class="alert alert-info text-center" role="alert">No recent moods to display.</div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white rounded-lg overflow-hidden">
                                <thead style="background-color: #F5F5F5;">
                                    <tr>
                                        <th class="py-3 px-4 text-left text-sm font-semibold text-secondary-light">Date</th>
                                        <th class="py-3 px-4 text-left text-sm font-semibold text-secondary-light">Mood</th>
                                        <th class="py-3 px-4 text-left text-sm font-semibold text-secondary-light">Note</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentMoods as $mood)
                                        <tr class="border-b border-light-gray last:border-b-0">
                                            <td class="py-3 px-4 text-primary-dark">{{ \Carbon\Carbon::parse($mood->entry_date)->format('M d, Y') }}</td>
                                            <td class="py-3 px-4 text-primary-dark">
                                                @switch($mood->mood_type)
                                                    @case('Happy') 😊 Happy @break
                                                    @case('Sad') 😥 Sad @break
                                                    @case('Angry') 😠 Angry @break
                                                    @case('Excited') 🤩 Excited @break
                                                    @case('Anxious') 😰 Anxious @break
                                                    @case('Calm') 😌 Calm @break
                                                    @case('Cheerful') 😄 Cheerful @break
                                                    @case('Neutral') 😐 Neutral @break
                                                    @case('Depressed') 😔 Depressed @break
                                                    @default {{ $mood->mood_type }}
                                                @endswitch
                                            </td>
                                            <td class="py-3 px-4 text-secondary-light">{{ $mood->note ?? 'N/A' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4 text-right">
                            <a href="{{ route('moods.index') }}" class="text-primary-accent hover:underline text-sm font-medium">View All Moods &rarr;</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Centralized color mapping for moods
                const moodColors = {
                    'Happy': 'rgba(255, 215, 0, 0.8)',   // Gold
                    'Cheerful': 'rgba(168, 218, 220, 0.8)', // Light Blue/Green
                    'Excited': 'rgba(149, 125, 173, 0.8)', // Purple
                    'Calm': 'rgba(162, 217, 206, 0.8)',  // Soft Teal
                    'Neutral': 'rgba(189, 189, 189, 0.8)', // Gray
                    'Anxious': 'rgba(244, 162, 97, 0.8)',  // Orange
                    'Sad': 'rgba(93, 138, 168, 0.8)',     // Muted Blue
                    'Angry': 'rgba(231, 111, 81, 0.8)',   // Red-Orange
                    'Depressed': 'rgba(74, 110, 125, 0.8)' // Dark Teal/Blue
                };

                const moodBorderColors = {
                    'Happy': 'rgba(255, 215, 0, 1)',
                    'Cheerful': 'rgba(168, 218, 220, 1)',
                    'Excited': 'rgba(149, 125, 173, 1)',
                    'Calm': 'rgba(162, 217, 206, 1)',
                    'Neutral': 'rgba(189, 189, 189, 1)',
                    'Anxious': 'rgba(244, 162, 97, 1)',
                    'Sad': 'rgba(93, 138, 168, 1)',
                    'Angry': 'rgba(231, 111, 81, 1)',
                    'Depressed': 'rgba(74, 110, 125, 1)'
                };

                // Mood Summary Chart (Monthly)
                var chartLabels = @json($chartLabels ?? []);
                var chartData = @json($chartData ?? []);
                
                if (chartLabels.length > 0) {
                    var ctx = document.getElementById('moodChart').getContext('2d');
                    var moodChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: chartLabels,
                            datasets: [{
                                label: 'Moods in the Last 30 Days',
                                data: chartData,
                                backgroundColor: chartLabels.map(label => moodColors[label] || 'rgba(200, 200, 200, 0.8)'),
                                borderColor: chartLabels.map(label => moodBorderColors[label] || 'rgba(200, 200, 200, 1)'),
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        stepSize: 1,
                                        font: { family: 'Instrument Sans, sans-serif' }
                                    },
                                    grid: { color: 'rgba(200, 200, 200, 0.2)' }
                                },
                                x: {
                                    grid: { display: false },
                                    ticks: { font: { family: 'Instrument Sans, sans-serif' } }
                                }
                            },
                            plugins: {
                                legend: { display: false },
                                tooltip: {
                                    backgroundColor: 'rgba(0, 0, 0, 0.7)',
                                    bodyFont: { family: 'Instrument Sans, sans-serif' },
                                    titleFont: { family: 'Instrument Sans, sans-serif' }
                                }
                            }
                        }
                    });
                }

                // Weekly Mood Tracker Chart
                var weeklyDates = @json($weeklyDates ?? []);
                var weeklyMoodData = @json($weeklyMoodData ?? []);
                
                if (weeklyMoodData.some(data => data !== null)) {
                    var ctx = document.getElementById('weeklyMoodChart').getContext('2d');
                    // This array maps the numerical mood values (0-8) to their string labels.
                    // Ensure this order matches the numerical mapping in your controller.
                    const moodLabels = ['Angry', 'Sad', 'Anxious', 'Calm', 'Excited', 'Happy', 'Cheerful', 'Neutral', 'Depressed']; 
                    // Note: If you add new moods in the controller, ensure they are added here
                    // and that the numerical scale in the controller aligns with these labels.

                    var weeklyMoodChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: weeklyDates,
        datasets: [{
            label: 'Your Mood',
            data: weeklyMoodData,
            backgroundColor: weeklyMoodData.map(value => moodColors[moodLabels[value]] || 'rgba(200, 200, 200, 0.8)'),
            borderColor: weeklyMoodData.map(value => moodBorderColors[moodLabels[value]] || 'rgba(200, 200, 200, 1)'),
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                min: 0,
                max: 8,
                ticks: {
                    callback: function(value, index, ticks) {
                        // **UPDATED: This array now matches your desired order**
                        const displayLabels = ['Depressed', 'Angry', 'Sad', 'Anxious', 'Neutral', 'Calm', 'Excited', 'Happy', 'Cheerful'];
                        return displayLabels[value];
                    },
                    stepSize: 1,
                    font: { family: 'Instrument Sans, sans-serif' }
                },
                title: {
                    display: true,
                    text: 'Mood Level',
                    font: { family: 'Instrument Sans, sans-serif', weight: 'bold' }
                },
                grid: { color: 'rgba(200, 200, 200, 0.2)' }
            },
            x: {
                ticks: { font: { family: 'Instrument Sans, sans-serif' } },
                title: {
                    display: true,
                    text: 'Date',
                    font: { family: 'Instrument Sans, sans-serif', weight: 'bold' }
                },
                grid: { display: false }
            }
        },
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.7)',
                bodyFont: { family: 'Instrument Sans, sans-serif' },
                titleFont: { family: 'Instrument Sans, sans-serif' },
                callbacks: {
                    label: function(context) {
                        // **UPDATED: This array also needs to be updated for the tooltip**
                        const displayLabels = ['Depressed', 'Angry', 'Sad', 'Anxious', 'Neutral', 'Calm', 'Excited', 'Happy', 'Cheerful'];
                        return 'Mood: ' + displayLabels[context.parsed.y];
                    }
                }
            }
        }
    }
});
                }
            });
        </script>
    @endpush
</x-app-layout>
