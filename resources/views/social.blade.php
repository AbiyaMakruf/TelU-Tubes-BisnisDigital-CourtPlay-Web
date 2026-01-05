@extends('layouts.app')

@section('title', 'Social Page')
@section('fullbleed', true)

@push('styles')
<style>
    .glass-card {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 16px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .glass-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
        border-color: rgba(163, 206, 20, 0.3);
    }
    .form-control-modern {
        background: rgba(0, 0, 0, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: white;
        border-radius: 50px;
        padding: 0.8rem 1.2rem;
    }
    .form-control-modern:focus {
        background: rgba(0, 0, 0, 0.3);
        border-color: var(--primary-300);
        color: white;
        box-shadow: 0 0 0 0.25rem rgba(163, 206, 20, 0.15);
    }
    .avatar-circle {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: #333;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        border: 2px solid var(--primary-300);
    }
    .avatar-img2 {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .avatar-initials-text2 {
        font-weight: bold;
        color: var(--primary-300);
    }
    .avatar-circle-sm {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #333;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        border: 1px solid var(--primary-300);
    }
    .avatar-img-sm {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .avatar-initials-text-sm {
        font-size: 0.8rem;
        font-weight: bold;
        color: var(--primary-300);
    }
    .chart-container {
        background: rgba(0, 0, 0, 0.2);
        border-radius: 12px;
        border: 1px solid rgba(255, 255, 255, 0.05);
    }
</style>
@endpush

@section('content')
<div class="container py-5 text-white">
    <div class="row justify-content-center">

        <!-- LEFT COLUMN -->
        <div class="col-lg-3 mb-4">

            <!-- Search Box -->
            <div class="glass-card p-4 mb-4">
                <h4 class="fw-bold text-primary-300 mb-3">Explore Users</h4>

                <div class="position-relative mb-3">

                <input type="text"
                    name="search"
                    id="searchInput"
                    class="form-control form-control-modern"
                    placeholder="Search username..."
                    autocomplete="off"
                    autocapitalize="off"
                    spellcheck="false"
                    value="{{ $searchTerm }}">

                <!-- Loader kecil di kanan -->
                <div id="searchLoading"
                    class="spinner-border text-primary-300"
                    role="status"
                    style="
                        display:none;
                        position:absolute;
                        right:15px;
                        top:25%;
                        width:1.2rem;
                        height:1.2rem;
                    ">
                </div>

            </div>
            </div>

            <!-- SEARCH RESULTS (AJAX updates this container) -->
            <div id="searchUserResults">

                @if($searchTerm)
                    <div class="glass-card p-4 mb-4">
                        <h4 class="fw-bold text-primary-300 d-flex justify-content-start mb-3">Search Results</h4>

                        @include('partials.user-list', ['users' => $users, 'userId' => $userId])
                    </div>
                @else
                    <!-- POPULAR -->
                    <div class="glass-card p-4 mb-4">
                        <h4 class="fw-bold text-primary-300 d-flex justify-content-start mb-3">Popular</h4>

                        <ul class="list-unstyled mb-0">
                            @foreach ($topFollowers as $user)
                                <li class="mb-3 d-flex justify-content-start align-items-center">
                                    <a href="{{ route('user.profile', $user->username) }}" class="d-flex align-items-center avatar-link text-decoration-none">
                                        <div class="avatar-circle">
                                            @if($user->profile_picture_url)
                                                <img src="{{ $user->profile_picture_url }}" class="avatar-img2">
                                            @else
                                                <span class="avatar-initials-text2">
                                                    {{ strtoupper(substr($user->first_name, 0, 1) . substr($user->last_name, 0, 1)) }}
                                                </span>
                                            @endif
                                        </div>
                                    </a>

                                    <div class="ms-3">
                                        <a href="{{ route('user.profile', $user->username) }}" class="text-white fw-semibold d-block text-decoration-none">
                                            {{ $user->username }}
                                        </a>
                                        <div class="small text-white-50">{{ $user->followers_count_formatted }} Followers</div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- LATEST USERS -->
                    <div class="glass-card p-4 mb-4">
                        <h4 class="fw-bold text-primary-300 d-flex justify-content-start mb-3">New Coming</h4>

                        <ul class="list-unstyled mb-0">
                            @foreach ($latestUsers as $user)
                                <li class="mb-3 d-flex justify-content-start align-items-center">
                                    <a href="{{ route('user.profile', $user->username) }}" class="d-flex align-items-center avatar-link text-decoration-none">
                                        <div class="avatar-circle">
                                            @if($user->profile_picture_url)
                                                <img src="{{ $user->profile_picture_url }}" class="avatar-img2">
                                            @else
                                                <span class="avatar-initials-text2">
                                                    {{ strtoupper(substr($user->first_name, 0, 1) . substr($user->last_name, 0, 1)) }}
                                                </span>
                                            @endif
                                        </div>
                                    </a>

                                    <div class="ms-3">
                                        <a href="{{ route('user.profile', $user->username) }}" class="text-white fw-semibold d-block text-decoration-none">
                                            {{ $user->username }}
                                        </a>
                                        <div class="small text-white-50">
                                            Joined {{ $user->created_at->format('M d') }}
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

            </div><!-- end searchUserResults -->

        </div>



        <!-- RIGHT COLUMN: PROJECTS -->
        <div class="col-lg-9 mb-4">
            @foreach($latestProjects as $project)
                <div class="glass-card mb-4 p-4">

                    <!-- Header -->
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <div>
                            <div class="d-flex align-items-center mb-2">
                                <a href="{{ route('user.profile', $project->user->username) }}" class="d-flex align-items-center me-2 text-decoration-none" >
                                    <div class="avatar-circle-sm me-2">
                                        @if($project->user->profile_picture_url)
                                            <img src="{{ $project->user->profile_picture_url }}" class="avatar-img-sm">
                                        @else
                                            <span class="avatar-initials-text-sm">
                                                {{ strtoupper(substr($project->user->first_name, 0, 1) . substr($project->user->last_name, 0, 1)) }}
                                            </span>
                                        @endif
                                    </div>
                                    <span class="text-white fw-semibold">{{ $project->user->username }}</span>
                                </a>
                                <span class="text-white-50 small">• {{ \Carbon\Carbon::parse($project->upload_date)->diffForHumans() }}</span>
                            </div>
                            <h3 class="fw-bold text-primary-300 mb-0">{{ $project->project_name }}</h3>
                        </div>

                        <div class="d-flex gap-4">
                            <div class="text-end">
                                <div class="small text-white-50">Duration</div>
                                <div class="fw-bold text-white">
                                    {{ gmdate('i:s', $project->projectDetails->video_duration ?? 0) }}
                                </div>
                            </div>
                            <div class="text-end border-start border-secondary ps-4">
                                <div class="small text-white-50">Style</div>
                                <div class="fw-bold text-white">
                                    {{ $project->major_movement ?? 'Balanced' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Details -->
                    <div class="row g-4">

                        <!-- LEFT SPIDER CHART -->
                        <div class="col-md-6">
                            <div class="chart-container p-3 h-100 d-flex justify-content-center align-items-center">
                                <canvas id="spiderChart{{ $project->id }}" style="max-height: 250px;"></canvas>
                            </div>
                        </div>

                        <!-- RIGHT THUMBNAIL -->
                        @if($project->link_image_thumbnail)
                        <div class="col-md-6">
                            <div class="chart-container p-3 h-100 d-flex justify-content-center align-items-center overflow-hidden">
                                <img src="{{ $project->link_image_thumbnail }}"
                                    class="img-fluid rounded"
                                    style="max-height: 250px; object-fit:contain;">
                            </div>
                        </div>
                        @endif

                        <!-- HEATMAP BLOCK -->
                        @if($project->link_image_heatmap_player_horizontal)
                        <div class="col-12">
                            <div class="chart-container p-2">
                                <img src="{{ $project->link_image_heatmap_player_horizontal }}"
                                    class="w-100 rounded" style="max-height: 200px; object-fit: cover;">
                            </div>
                        </div>
                        @endif

                    </div>


                </div>
            @endforeach
        </div>

    </div>
</div>



{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

{{-- Spider Charts --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
    @foreach($latestProjects as $project)
        new Chart(document.getElementById('spiderChart{{ $project->id }}'), {
            type: 'radar',
            data: {
                labels: ['Forehand', 'Backhand', 'Serve', 'Ready'],
                datasets: [{
                    data: [
                        {{ $project->projectDetails->forehand_count ?? 0 }},
                        {{ $project->projectDetails->backhand_count ?? 0 }},
                        {{ $project->projectDetails->serve_count ?? 0 }},
                        {{ $project->projectDetails->ready_position_count ?? 0 }}
                    ],
                    borderColor: '#a3ce14',
                    backgroundColor: 'rgba(163,206,20,0.2)',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                scales: {
                    r: {
                        angleLines: { color: '#333' },
                        grid: { color: '#444' },
                        pointLabels: { color: '#ccc' },
                        ticks: { display: false, beginAtZero: true }
                    }
                },
                plugins: { legend: { display: false } }
            }
        });
    @endforeach
});
</script>

{{-- AJAX Live Search --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
    const input = document.getElementById("searchInput");
    const container = document.getElementById("searchUserResults");
    const loader = document.getElementById("searchLoading");

    let debounceTimer = null;

    input.addEventListener("input", () => {
        clearTimeout(debounceTimer);

        loader.style.display = "block";

        debounceTimer = setTimeout(() => {
            let query = input.value.trim();

            fetch(`?search=${encodeURIComponent(query)}`, {
                headers: { "X-Requested-With": "XMLHttpRequest" }
            })
            .then(res => res.json())
            .then(data => {
                container.innerHTML = data.html;
            })
            .finally(() => {
                loader.style.display = "none";
            });

        }, 300);
    });
});
</script>

@endsection
