<x-app-layout>
    {{-- Push custom styles to the head section of the layout --}}
    @push('styles')
        <style>
            body {
                background-color: #F5F5F5; /* Primary Background */
            }
            .card {
                background-color: #FFFFFF; /* Surface / Card Background */
                border-radius: 1.5rem !important; /* More rounded corners */
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08) !important;
                transition: all 0.3s ease; /* Smooth transition for hover effects */
            }
            .card:hover {
                box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15) !important;
                transform: translateY(-5px); /* Lift the card on hover */
            }
            .table-light th {
                font-weight: 600;
                color: #777777; /* Secondary Text */
            }
            .table-hover tbody tr:hover {
                background-color: #f8f9fa; /* Lighter hover color */
                cursor: pointer;
            }
            .btn-primary {
                background-color: #A2D9CE; /* Primary Accent */
                border-color: #A2D9CE;
                transition: transform 0.2s ease-in-out;
                color: #333333; /* Primary Text */
            }
            .btn-primary:hover {
                transform: translateY(-2px);
                background-color: #82CBBF; /* Slightly darker teal on hover */
                border-color: #82CBBF;
            }
            .btn-warning {
                background-color: #E74C3C; /* Error / Alert color */
                border-color: #E74C3C;
                transition: transform 0.2s ease-in-out;
                color: #FFFFFF;
            }
            .btn-warning:hover {
                transform: translateY(-2px);
                background-color: #D62C1A;
                border-color: #D62C1A;
            }
            .btn-secondary {
                background-color: #B3E0FF; /* Secondary Accent */
                border-color: #B3E0FF;
                transition: transform 0.2s ease-in-out;
                color: #333333; /* Primary Text */
            }
            .btn-secondary:hover {
                transform: translateY(-2px);
                background-color: #92CFFF;
                border-color: #92CFFF;
            }
            .text-primary-accent {
                color: #A2D9CE !important; /* Primary Accent */
            }
            .text-secondary-light {
                color: #777777 !important; /* Secondary Text */
            }
            .text-primary-dark {
                color: #333333 !important; /* Primary Text */
            }
            .alert-success {
                background-color: #D4EDDA; /* Light green for success alerts */
                border-color: #C3E6CB;
                color: #155724;
            }
            .alert-info {
                background-color: #D1ECF1; /* Light blue for info alerts */
                border-color: #BEE5EB;
                color: #0C5460;
            }
        </style>
    @endpush

    <div class="container mt-5">
        {{-- Success/Error Alert --}}
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
        @if(session('status'))
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Header with Title, Streak Badge, and Buttons --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="text-primary-dark">Your Mood History</h2>
            <div class="d-flex align-items-center">
                {{-- Streak Badge --}}
                @if(isset($streakCount) && $streakCount >= 3)
                    <div class="alert alert-info py-2 px-3 m-0 rounded-pill shadow-sm d-flex align-items-center me-3">
                        <i class="fas fa-fire me-2 text-warning"></i>
                        <span class="fw-bold text-primary-dark">Awesome! You're on a {{ $streakCount }}-day streak!</span>
                    </div>
                @endif
                <a href="{{ route('moods.trashed') }}" class="btn btn-warning me-2">
                    <i class="fas fa-trash-alt"></i> View Trashed Moods
                </a>
                <a href="{{ route('moods.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Log New Mood
                </a>
            </div>
        </div>

        {{-- Date Range Filter Form --}}
        <div class="card mb-4 shadow-sm">
            <div class="card-body">
                <h5 class="card-title text-primary-dark">Filter by Date Range</h5>
                <form method="GET" action="{{ route('moods.index') }}" class="row g-3 align-items-center">
                    <div class="col-md-4">
                        <label for="from_date" class="form-label visually-hidden text-secondary-light">From Date</label>
                        <input type="date" class="form-control" id="from_date" name="from_date" value="{{ request('from_date') }}">
                    </div>
                    <div class="col-md-4">
                        <label for="to_date" class="form-label visually-hidden text-secondary-light">To Date</label>
                        <input type="date" class="form-control" id="to_date" name="to_date" value="{{ request('to_date') }}">
                    </div>
                    <div class="col-md-4 d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary me-2">Filter</button>
                        {{-- Export PDF Button --}}
                        <a href="{{ route('moods.export.pdf', ['from_date' => request('from_date'), 'to_date' => request('to_date')]) }}" 
                           class="btn btn-secondary">
                             <i class="fas fa-file-pdf"></i> Export PDF
                        </a>
                    </div>
                </form>
            </div>
        </div>
        
        {{-- Mood History Table --}}
        @if($moods->isEmpty())
            <div class="alert alert-info text-center" role="alert">
                You haven't logged any moods yet. <a href="{{ route('moods.create') }}" class="text-primary-accent hover:underline">Log your first mood!</a>
            </div>
        @else
            <div class="bg-white p-4 rounded-4 shadow-sm overflow-hidden">
                <h5 class="mb-4 fs-4 fw-bold text-primary-dark">All Mood Entries</h5>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-secondary-light">Date</th>
                                <th class="text-secondary-light">Mood</th>
                                <th class="text-secondary-light">Note</th>
                                <th class="text-center text-secondary-light">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($moods as $mood)
                                <tr class="border-bottom">
                                    <td class="py-3 text-primary-dark">{{ \Carbon\Carbon::parse($mood->entry_date)->format('M d, Y') }}</td>
                                    <td class="py-3 text-primary-dark">
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
                                    <td class="py-3 text-secondary-light">{{ $mood->note ?? 'N/A' }}</td>
                                    <td class="text-center py-3">
                                        {{-- Edit Button --}}
                                        <a href="{{ route('moods.edit', $mood->id) }}" class="btn btn-sm btn-outline-info me-1" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        {{-- Delete Form (Soft Delete) --}}
                                        <form action="{{ route('moods.destroy', $mood->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Soft Delete" onclick="return confirm('Are you sure you want to soft delete this entry?')">
                                                <i class="fas fa-trash"></i>
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

    {{-- No charts here anymore, they are on the dashboard. --}}
    @push('scripts')
        {{-- No Chart.js script needed here anymore --}}
    @endpush
</x-app-layout>
