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

            .table th {
                font-weight: 600;
                color: #777777;
                text-transform: uppercase;
                font-size: 0.85rem;
                letter-spacing: 0.05em;
            }

            .table-hover tbody tr:hover {
                background-color: #fafafa;
                cursor: pointer;
            }

            .btn-gradient-primary {
                background-image: linear-gradient(to right, #A2D9CE 0%, #B3E0FF 100%);
                border: none;
                color: #333333;
                font-weight: 600;
                border-radius: 0.75rem;
                padding: 0.6rem 1.5rem;
                box-shadow: 0 4px 15px rgba(162, 217, 206, 0.3);
                transition: all 0.3s ease;
                background-size: 200% auto;
            }

            .btn-gradient-primary:hover {
                background-position: right center;
                box-shadow: 0 6px 20px rgba(162, 217, 206, 0.5);
                transform: translateY(-2px);
            }

            .btn-outline-muted {
                border: 1px solid #ccc;
                background-color: #fff;
                color: #555;
                font-weight: 500;
                border-radius: 0.75rem;
                padding: 0.6rem 1.2rem;
                transition: all 0.2s ease-in-out;
            }

            .btn-outline-muted:hover {
                background-color: #eee;
                color: #222;
                border-color: #bbb;
            }

            .alert-modern {
                border-radius: 0.75rem;
                padding: 1.5rem;
                font-weight: 600;
                border: none;
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            }

            .alert-info {
                background-color: #D1ECF1;
                color: #0C5460;
            }

            .actions-column {
                min-width: 150px;
                text-align: center;
            }

            /* Styled heading like Edit Your Mood */
            .page-heading {
                font-size: 1.6rem;
                font-weight: 700;
                color: #72c4b3;
                margin-top: 2rem;
                margin-bottom: 1.5rem;
            }
        </style>
    @endpush

    <div class="container py-5 mt-5">

        {{-- Back Button --}}
        <div class="d-flex justify-content-end mb-3">
            <a href="{{ route('moods.index') }}" class="btn btn-outline-muted">
                <i class="fas fa-arrow-left me-2"></i> Back to Mood History
            </a>
        </div>

        {{-- Styled Heading --}}
        <h2 class="page-heading">Trashed Mood Entries</h2>

        {{-- Alert if no trashed moods --}}
        @if($trashedMoods->isEmpty())
            <div class="alert alert-info alert-modern text-center" role="alert">
                No deleted mood entries found.
            </div>
        @else
            <div class="card">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Mood</th>
                                <th>Note</th>
                                <th>Deleted At</th>
                                <th class="actions-column">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($trashedMoods as $mood)
                                <tr>
                                    <td class="text-primary-dark">{{ \Carbon\Carbon::parse($mood->entry_date)->format('M d, Y') }}</td>
                                    <td class="text-primary-dark">
                                        @switch($mood->mood_type)
                                            @case('Happy') 😊 Happy @break
                                            @case('Sad') 😥 Sad @break
                                            @case('Angry') 😠 Angry @break
                                            @case('Excited') 🤩 Excited @break
                                            @case('Anxious') 😰 Anxious @break
                                            @case('Calm') 😌 Calm @break
                                            @default {{ $mood->mood_type }}
                                        @endswitch
                                    </td>
                                    <td class="text-secondary-light">{{ $mood->note ?? 'N/A' }}</td>
                                    <td class="text-secondary-light">{{ $mood->deleted_at->format('M d, Y H:i:s') }}</td>
                                    <td class="actions-column">
                                        <form action="{{ route('moods.restore', $mood->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-gradient-primary btn-sm" data-bs-toggle="tooltip" title="Restore this mood">
                                                <i class="fas fa-undo-alt"></i> Restore
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
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
