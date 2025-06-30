<x-app-layout>
    {{-- Push custom styles --}}
    @push('styles')
        <style>
            body {
                background-color: #F5F5F5; /* Primary Background */
            }
            .card-modern {
                background-color: #FFFFFF; /* Surface / Card Background */
                border-radius: 1.5rem; /* More rounded corners, consistent with other cards */
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08); /* Softer shadow */
                transition: all 0.3s ease-in-out;
            }
            .card-modern:hover {
                box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
                transform: translateY(-5px);
            }
            .text-primary-dark {
                color: #333333 !important; /* Primary Text */
            }
            .text-secondary-light {
                color: #777777 !important; /* Secondary Text */
            }
            .text-primary-accent {
                color: #A2D9CE !important; /* Primary Accent */
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
            .form-control-modern, .form-select-modern {
                border-radius: 0.5rem;
                border: 1px solid #E0E0E0; /* Border / Divider */
                padding: 0.75rem 1rem;
                font-size: 1rem;
                color: #333333;
                transition: border-color 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
            }
            .form-control-modern:focus, .form-select-modern:focus {
                border-color: #A2D9CE;
                box-shadow: 0 0 0 0.25rem rgba(162, 217, 206, 0.25);
                outline: none;
            }
            .alert-success {
                background-color: #D4EDDA; /* Success */
                border-color: #C3E6CB;
                color: #2ECC71; /* Vibrant Green */
            }
            .alert-danger {
                background-color: #F8D7DA; /* Light red */
                border-color: #F5C6CB;
                color: #E74C3C; /* Soft Red */
            }
            .alert-warning {
                background-color: #FFF3CD;
                border-color: #FFECB5;
                color: #856404;
            }
            /* New style for feedback message */
            .feedback-message {
                margin-top: 0.5rem;
                font-size: 0.875rem;
            }
        </style>
    @endpush

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card-modern">
                    <div class="card-header bg-white text-primary-dark border-light-gray pb-3 pt-4 px-4 rounded-top-4">
                        <h4 class="mb-0 fs-3 fw-bold">Log Your Mood</h4>
                    </div>
                    <div class="card-body p-4">
                        {{-- Display success or error messages --}}
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        {{-- Display validation errors --}}
                        @if ($errors->any())
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        {{-- Mood Entry Form --}}
                        <form action="{{ route('moods.store') }}" method="POST">
                            @csrf

                            {{-- Mood Selection --}}
                            <div class="mb-4">
                                <label for="mood_type" class="form-label text-primary-dark fw-bold mb-2">How are you feeling?</label>
                                <select class="form-select form-select-lg form-select-modern" id="mood_type" name="mood_type" required>
                                    <option value="">Choose a mood...</option>
                                    {{-- Order based on numerical scale in MoodController: Angry=0, Sad=1, Anxious=2, Calm=3, Excited=4, Happy=5, Cheerful=6, Neutral=7, Depressed=8 --}}
                                    <option value="Happy" {{ old('mood_type') == 'Happy' ? 'selected' : '' }}>😊 Happy</option>
                                    <option value="Cheerful" {{ old('mood_type') == 'Cheerful' ? 'selected' : '' }}>😄 Cheerful</option>
                                    <option value="Excited" {{ old('mood_type') == 'Excited' ? 'selected' : '' }}>🤩 Excited</option>
                                    <option value="Calm" {{ old('mood_type') == 'Calm' ? 'selected' : '' }}>😌 Calm</option>
                                    <option value="Neutral" {{ old('mood_type') == 'Neutral' ? 'selected' : '' }}>😐 Neutral</option>
                                    <option value="Anxious" {{ old('mood_type') == 'Anxious' ? 'selected' : '' }}>😰 Anxious</option>
                                    <option value="Sad" {{ old('mood_type') == 'Sad' ? 'selected' : '' }}>😥 Sad</option>
                                    <option value="Angry" {{ old('mood_type') == 'Angry' ? 'selected' : '' }}>😠 Angry</option>
                                    <option value="Depressed" {{ old('mood_type') == 'Depressed' ? 'selected' : '' }}>😔 Depressed</option>
                                </select>
                                @error('mood_type')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Optional Note --}}
                            <div class="mb-4">
                                <label for="note" class="form-label text-primary-dark fw-bold mb-2">Add a short note (optional):</label>
                                <textarea class="form-control form-control-modern" id="note" name="note" rows="4" placeholder="How are you feeling today?">{{ old('note') }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label for="entry_date" class="form-label text-primary-dark fw-bold mb-2">Date</label>
                                <input type="date"
                                    class="form-control form-control-modern"
                                    id="entry_date"
                                    name="entry_date"
                                    value="{{ old('entry_date', \Carbon\Carbon::today()->toDateString()) }}"
                                    required>
                                {{-- Feedback message for date --}}
                                <div id="dateFeedback" class="feedback-message text-danger" style="display: none;">
                                    You have already logged a mood for this date.
                                </div>
                                @error('entry_date')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Submit Button --}}
                            <div class="d-grid gap-2 mt-4">
                                <button type="submit" id="logMoodBtn" class="btn-accent btn-lg">Log My Mood</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const entryDateInput = document.getElementById('entry_date');
                const logMoodBtn = document.getElementById('logMoodBtn');
                const dateFeedback = document.getElementById('dateFeedback');

                // Function to check if a mood exists for the selected date
                async function checkMoodExists() {
                    const selectedDate = entryDateInput.value;

                    if (!selectedDate) {
                        logMoodBtn.disabled = true;
                        dateFeedback.style.display = 'none';
                        return;
                    }

                    try {
                        const response = await fetch(`/moods/check-exists?entry_date=${selectedDate}`, {
                            method: 'GET',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest', // Important for Laravel's AJAX detection
                                'Content-Type': 'application/json',
                            }
                        });
                        const data = await response.json();

                        if (data.exists) {
                            logMoodBtn.disabled = true;
                            dateFeedback.style.display = 'block';
                            dateFeedback.style.color = '#E74C3C'; // Error color
                        } else {
                            logMoodBtn.disabled = false;
                            dateFeedback.style.display = 'none';
                        }
                    } catch (error) {
                        console.error('Error checking mood existence:', error);
                        // In case of an error, default to enabling the button but show a warning
                        logMoodBtn.disabled = false;
                        dateFeedback.style.display = 'block';
                        dateFeedback.textContent = 'Could not verify date. Please try again.';
                        dateFeedback.style.color = '#856404'; // Warning color
                    }
                }

                // Initial check when the page loads (for the default date)
                checkMoodExists();

                // Add event listener for date input change
                entryDateInput.addEventListener('change', checkMoodExists);
            });
        </script>
    @endpush
</x-app-layout>
