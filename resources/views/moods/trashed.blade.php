<x-app-layout>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Trashed Mood Entries</h2>
        <a href="{{ route('moods.index') }}" class="btn btn-secondary">Back to Mood History</a>
    </div>

    @if($trashedMoods->isEmpty())
        <div class="alert alert-info" role="alert">
            No deleted mood entries found.
        </div>
    @else
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Mood</th>
                    <th>Note</th>
                    <th>Deleted At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($trashedMoods as $mood)
                    <tr>
                        <td>{{ $mood->entry_date }}</td>
                        <td>{{ $mood->mood_type }}</td>
                        <td>{{ $mood->note }}</td>
                        <td>{{ $mood->deleted_at->format('Y-m-d H:i:s') }}</td>
                        <td>
                            <form action="{{ route('moods.restore', $mood->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm">Restore</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
</x-app-layout>