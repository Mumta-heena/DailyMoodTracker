<x-app-layout>
    <div class="container mt-5">
        <div class="card shadow-sm mx-auto" style="max-width: 600px;">
            <div class="card-header bg-primary text-white text-center">
                <h4 class="mb-0">Edit Your Mood</h4>
            </div>
            <div class="card-body p-4">
                {{-- The form's action will point to the update route with the mood's ID --}}
                <form action="{{ route('moods.update', $mood->id) }}" method="POST">
                    @csrf
                    {{-- Use the PUT method for updates as per REST conventions --}}
                    @method('PUT')

                    {{-- Entry Date (Read-only for editing) --}}
                    <div class="mb-3">
                        <label for="entry_date" class="form-label">Date</label>
                        <input type="date" class="form-control" id="entry_date" name="entry_date" value="{{ $mood->entry_date }}" required readonly>
                    </div>

                    {{-- Mood Type --}}
                    <div class="mb-3">
                        <label for="mood_type" class="form-label">How are you feeling?</label>
                        <select class="form-select" id="mood_type" name="mood_type" required>
                            <option value="">Select a mood</option>
                            <option value="Happy" {{ $mood->mood_type == 'Happy' ? 'selected' : '' }}>😊 Happy</option>
                            <option value="Sad" {{ $mood->mood_type == 'Sad' ? 'selected' : '' }}>😥 Sad</option>
                            <option value="Angry" {{ $mood->mood_type == 'Angry' ? 'selected' : '' }}>😠 Angry</option>
                            <option value="Excited" {{ $mood->mood_type == 'Excited' ? 'selected' : '' }}>🤩 Excited</option>
                            <option value="Anxious" {{ $mood->mood_type == 'Anxious' ? 'selected' : '' }}>😰 Anxious</option>
                            <option value="Calm" {{ $mood->mood_type == 'Calm' ? 'selected' : '' }}>😌 Calm</option>
                        </select>
                    </div>

                    {{-- Note --}}
                    <div class="mb-4">
                        <label for="note" class="form-label">Add a note (optional)</label>
                        <textarea class="form-control" id="note" name="note" rows="3" placeholder="What's on your mind?">{{ $mood->note }}</textarea>
                    </div>

                    {{-- Form Actions --}}
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fas fa-save me-2"></i> Update Mood
                        </button>
                        <a href="{{ route('moods.index') }}" class="btn btn-outline-secondary btn-sm">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>