<x-app-layout>
    @push('styles')
        <style>
            body {
                background-color: #F5F5F5;
                font-family: 'Inter', sans-serif;
            }
            th.actions-column {
                min-width: 200px;
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

            .btn-icon-soft {
                background-color: rgba(162, 217, 206, 0.1);
                color: #A2D9CE;
                border: 1px solid rgba(162, 217, 206, 0.2);
                border-radius: 0.5rem;
                width: 40px;
                height: 40px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                transition: all 0.3s ease;
            }

            .btn-icon-soft:hover {
                background-color: #A2D9CE;
                color: #FFFFFF;
                transform: translateY(-1px);
            }

            .text-primary-accent {
                color: #A2D9CE !important;
            }

            .alert-modern {
                border-radius: 0.75rem;
                padding: 1.5rem;
                font-weight: 600;
                border: none;
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            }

            .alert-success { background-color: #D4EDDA; color: #155724; }
            .alert-info { background-color: #D1ECF1; color: #0C5460; }
            .alert-danger { background-color: #F8D7DA; color: #721C24; }

            .note-column {
                max-width: 300px;
                word-wrap: break-word;
                white-space: normal;
            }

            .spacing-section {
                margin-top: 4rem !important;
                margin-bottom: 4rem !important;
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

            .btn-filter {
                min-width: 110px;
                height: 40px;
                padding: 0.3rem 1rem;
                font-weight: 600;
                font-size: 0.95rem;
            }

            /* Styled heading for "Your Mood History" */
            .page-heading {
                font-size: 1.6rem;
                font-weight: 700;
                color: #72c4b3;
                margin-top: 2rem;
                margin-bottom: 0.5rem;
            }

            /* Improved input fields styling */
            input[type="date"].form-control-lg {
                border: 1.5px solid #A2D9CE;
                border-radius: 0.75rem;
                padding: 0.5rem 1rem;
                font-size: 1rem;
                color: #333;
                box-shadow: none;
                transition: border-color 0.3s ease, box-shadow 0.3s ease;
                background-color: #fff;
            }

            input[type="date"].form-control-lg:focus {
                border-color: #72c4b3; /* same as your page heading color */
                box-shadow: 0 0 8px 0 rgba(114, 196, 179, 0.6);
                outline: none;
                color: #000;
            }
        </style>
    @endpush

    <div class="container py-5 mt-5">
        {{-- Alerts --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show alert-modern spacing-section" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show alert-modern spacing-section" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if(session('status'))
            <div class="alert alert-info alert-dismissible fade show alert-modern spacing-section" role="alert">
                {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Header --}}
        <div class="row align-items-center mb-5">
            <div class="col-md-6 col-12 mb-3 mb-md-0">
                <h2 class="page-heading">Your Mood History</h2>
                <p class="text-secondary-light fs-6 mb-0">Track and manage your moods effectively.</p>
            </div>
            <div class="col-md-6 col-12 d-flex justify-content-md-end flex-wrap gap-3 gap-md-4">
                @if(isset($streakCount) && $streakCount >= 3)
                    <div class="alert alert-info py-2 px-3 m-0 rounded-pill shadow-sm d-flex align-items-center">
                        <i class="fas fa-fire me-2 text-warning"></i>
                        <span class="fw-bold text-primary-dark">You're on a {{ $streakCount }}-day streak!</span>
                    </div>
                @endif
                <a href="{{ route('moods.trashed') }}" class="btn btn-outline-muted">
                    <i class="fas fa-trash-alt me-2"></i> View Trashed
                </a>
                <a href="{{ route('moods.create') }}" class="btn btn-gradient-primary">
                    <i class="fas fa-plus me-2"></i> Log New Mood
                </a>
            </div>
        </div>

        {{-- Filter Section --}}
        <div class="card spacing-section">
            <div class="card-body">
                <h5 class="card-title text-primary-dark fw-bold mb-4">Filter by Date Range</h5>
                <form method="GET" action="{{ route('moods.index') }}" class="row gx-4 gy-3 align-items-end">
                    <div class="col-md-5">
                        <label for="from_date" class="form-label text-secondary-light fw-bold">FROM</label>
                        <input type="date" class="form-control form-control-lg" id="from_date" name="from_date" value="{{ request('from_date') }}">
                    </div>
                    <div class="col-md-5">
                        <label for="to_date" class="form-label text-secondary-light fw-bold">TO</label>
                        <input type="date" class="form-control form-control-lg" id="to_date" name="to_date" value="{{ request('to_date') }}">
                    </div>
                    <div class="col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-gradient-primary btn-filter">Filter</button>
                        <a href="{{ route('moods.export.pdf', ['from_date' => request('from_date'), 'to_date' => request('to_date')]) }}" class="btn btn-icon-soft" data-bs-toggle="tooltip" title="Export PDF">
                            <i class="fas fa-file-pdf"></i>
                        </a>
                    </div>
                </form>
            </div>
        </div>

        {{-- Mood Table --}}
        @if($moods->isEmpty())
            <div class="alert alert-info text-center alert-modern spacing-section" role="alert">
                You haven't logged any moods yet. <a href="{{ route('moods.create') }}" class="text-primary-accent text-decoration-none fw-bold">Log your first mood!</a>
            </div>
        @else
            <div class="card spacing-section">
                <div class="card-body">
                    <h5 class="mb-4 fs-4 fw-bold text-primary-dark">All Mood Entries</h5>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-secondary-light py-3 px-4">Date</th>
                                    <th class="text-secondary-light py-3 px-4">Mood</th>
                                    <th class="text-secondary-light py-3 px-4">Note</th>
                                    <th class="text-center text-secondary-light py-3 px-4 actions-column">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($moods as $mood)
                                    <tr class="border-bottom">
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
                                        <td class="py-3 px-4 text-secondary-light note-column">{{ $mood->note ?? 'N/A' }}</td>
                                        <td class="text-center py-3 px-4">
                                            <div class="d-flex justify-content-center align-items-center gap-2 flex-wrap">
                                                <a href="{{ route('moods.edit', $mood->id) }}" class="btn btn-icon-soft" data-bs-toggle="tooltip" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('moods.destroy', $mood->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="btn btn-icon-soft text-danger"
                                                        data-bs-toggle="tooltip"
                                                        title="Delete"
                                                        style="color: #E74C3C !important; border-color: #E74C3C30;"
                                                        onclick="return confirm('Are you sure you want to delete this entry?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    @if(method_exists($moods, 'links'))
                        <div class="mt-4">
                            {{ $moods->links('vendor.pagination.bootstrap-5') }}
                        </div>
                    @endif
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
