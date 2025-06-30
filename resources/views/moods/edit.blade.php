<x-app-layout>
    @push('styles')
        <style>
            body {
                background-color: #F5F5F5;
                font-family: 'Inter', sans-serif;
            }
            .card {
                background-color: #FFFFFF;
                border-radius: 1.5rem !important;
                box-shadow: 0 12px 30px rgba(0, 0, 0, 0.07) !important;
                border: none;
                padding: 2rem;
            }

            /* Updated buttons */
            .btn-gradient-primary-lg {
                background-image: linear-gradient(to right, #7FD8C0 0%, #A3D9FF 100%);
                border: none;
                color: #1B2733;
                font-weight: 700;
                border-radius: 2rem;
                padding: 0.75rem 2.5rem;
                font-size: 1.1rem;
                box-shadow: 0 6px 20px rgba(127, 216, 192, 0.4);
                transition: all 0.3s ease;
                background-size: 200% auto;
            }
            .btn-gradient-primary-lg:hover {
                background-position: right center;
                box-shadow: 0 10px 30px rgba(127, 216, 192, 0.6);
                transform: translateY(-3px);
                color: #0e1720;
            }

            .btn-outline-muted-lg {
                border: 2px solid #A2D9CE;
                background-color: #f0fbf9;
                color: #3a5f56;
                font-weight: 600;
                border-radius: 2rem;
                padding: 0.7rem 2.2rem;
                font-size: 1rem;
                transition: all 0.3s ease;
            }
            .btn-outline-muted-lg:hover {
                background-color: #A2D9CE;
                color: #fff;
                border-color: #7FD8C0;
                transform: translateY(-2px);
            }

            /* Enhanced input styles */
            input.form-control,
            select.form-select,
            textarea.form-control {
                border-radius: 1rem !important;
                border: 1.5px solid #d1d7db;
                box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.05);
                padding: 0.75rem 1.25rem;
                font-size: 1rem;
                transition: border-color 0.3s ease, box-shadow 0.3s ease;
                color: #344050;
                background-color: #fff;
            }

            input.form-control:focus,
            select.form-select:focus,
            textarea.form-control:focus {
                border-color: #A2D9CE;
                box-shadow: 0 0 8px rgba(162, 217, 206, 0.6);
                outline: none;
                background-color: #fff;
                color: #1b2733;
            }

            /* Placeholder styling */
            input.form-control::placeholder,
            textarea.form-control::placeholder {
                color: #9ea3ae;
                font-style: italic;
                opacity: 1;
            }
        </style>
    @endpush

    <div class="container py-5 mt-5">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">
                <div class="card">
                    <div class="mb-4 text-center">
                        <h3 class="fw-bold" style="font-size: 2rem; color: #47a58c;">
                            Edit Your Mood
                        </h3>
                        <p class="text-secondary-light">Update your mood entry below</p>
                    </div>

                    <form action="{{ route('moods.update', $mood->id) }}" method="POST" novalidate>
                        @csrf
                        @method('PUT')

                        {{-- Date --}}
                        <div class="mb-4">
                            <label for="entry_date" class="form-label fw-bold text-secondary-light">Date</label>
                            <input type="date" id="entry_date" name="entry_date" class="form-control" 
                                   value="{{ $mood->entry_date }}" required readonly
                                   style="background-color: #f8f9fa; cursor: not-allowed;">
                        </div>

                        {{-- Mood Type --}}
                        <div class="mb-4">
                            <label for="mood_type" class="form-label fw-bold text-secondary-light">How are you feeling?</label>
                            <select id="mood_type" name="mood_type" class="form-select" required>
                                <option value="" disabled {{ !$mood->mood_type ? 'selected' : '' }}>Select a mood</option>
                                <option value="Happy" {{ $mood->mood_type == 'Happy' ? 'selected' : '' }}>😊 Happy</option>
                                <option value="Sad" {{ $mood->mood_type == 'Sad' ? 'selected' : '' }}>😥 Sad</option>
                                <option value="Angry" {{ $mood->mood_type == 'Angry' ? 'selected' : '' }}>😠 Angry</option>
                                <option value="Excited" {{ $mood->mood_type == 'Excited' ? 'selected' : '' }}>🤩 Excited</option>
                                <option value="Anxious" {{ $mood->mood_type == 'Anxious' ? 'selected' : '' }}>😰 Anxious</option>
                                <option value="Calm" {{ $mood->mood_type == 'Calm' ? 'selected' : '' }}>😌 Calm</option>
                            </select>
                        </div>

                        {{-- Note --}}
                        <div class="mb-5">
                            <label for="note" class="form-label fw-bold text-secondary-light">Add a note (optional)</label>
                            <textarea id="note" name="note" rows="4" class="form-control" placeholder="What's on your mind?">{{ $mood->note }}</textarea>
                        </div>

                        {{-- Actions --}}
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                            <a href="{{ route('moods.index') }}" 
                               class="btn btn-outline-muted-lg" 
                               data-bs-toggle="tooltip" 
                               title="Cancel and go back">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="btn btn-gradient-primary-lg d-flex align-items-center" 
                                    data-bs-toggle="tooltip" 
                                    title="Save changes">
                                <i class="fas fa-save me-2"></i> Update Mood
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                tooltipTriggerList.map(function (el) {
                    return new bootstrap.Tooltip(el);
                });
            });
        </script>
    @endpush
</x-app-layout>
