<x-app-layout>
    <div><h1>TESTING CONTENT</h1></div>
    <div class="container mt-5">
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white">
                <h4 class="mb-0">Weekly Mood Summary (This Week)</h4>
            </div>
            <div class="card-body">
                @if(empty($chartLabels))
                    <div class="alert alert-info text-center" role="alert">
                        No moods logged for this week yet.
                    </div>
                @else
                    <canvas id="weeklyMoodChart" style="height: 400px;"></canvas>
                @endif
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('weeklyMoodChart').getContext('2d');
            const labels = @json($chartLabels ?? []);
            const data = @json($chartValues ?? []);

            // Define colors for each mood type
            const colors = {
                'Happy': '#28a745',   // Green
                'Excited': '#ffc107', // Yellow
                'Calm': '#17a2b8',    // Cyan
                'Sad': '#6c757d',     // Gray
                'Anxious': '#dc3545', // Red
                'Angry': '#dc3545'    // Red
            };

            const backgroundColors = labels.map(label => colors[label] || '#6c757d');

            const weeklyMoodChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Moods Logged This Week',
                        data: data,
                        backgroundColor: backgroundColors,
                        borderColor: backgroundColors.map(color => color.replace('0.2', '1')),
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Number of Times Logged'
                            },
                            ticks: {
                                stepSize: 1,
                                precision: 0
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Mood Type'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                title: (context) => context[0].label,
                                label: (context) => `${context.parsed.y} times`
                            }
                        }
                    }
                }
            });
        });
    </script>
</x-app-layout>