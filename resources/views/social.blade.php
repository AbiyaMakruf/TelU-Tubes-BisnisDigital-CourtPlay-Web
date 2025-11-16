@extends('layouts.app')

@section('title', 'Social Page')
@section('fullbleed', true)

@section('content')
<div class="container py-5 text-white">
    <div class="row justify-content-center">

        <!-- LEFT COLUMN -->
        <div class="col-lg-3 mb-4">

            <!-- Search Box -->
            <div class="card bg-black-200 shadow rounded-lg p-3 mb-3">
                <h4 class="fw-bold text-primary-300">Explore Users</h4>

                <div class="position-relative mb-3">

                <input type="text"
                    name="search"
                    id="searchInput"
                    class="form-control input-custom"
                    placeholder="Search username"
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
                        right:10px;
                        top:30%;
                        width:1.2rem;
                        height:1.2rem;
                    ">
                </div>

            </div>
            </div>

            <!-- SEARCH RESULTS (AJAX updates this container) -->
            <div id="searchUserResults">

                @if($searchTerm)
                    <div class="card bg-black-200 shadow rounded-lg p-4 mb-4">
                        <h4 class="fw-bold text-primary-300 d-flex justify-content-start mb-3">Search Results</h4>

                        @include('partials.user-list', ['users' => $users, 'userId' => $userId])
                    </div>
                @else
                    <!-- POPULAR -->
                    <div class="card bg-black-200 shadow rounded-lg p-4 mb-4">
                        <h4 class="fw-bold text-primary-300 d-flex justify-content-start mb-3">Popular</h4>

                        <ul class="list-unstyled">
                            @foreach ($topFollowers as $user)
                                <li class="mb-3 d-flex justify-content-start">
                                    <a href="{{ route('user.profile', $user->username) }}" class="d-flex align-items-center">
                                        <div class="avatar-circle">
                                            @if($user->profile_picture_url)
                                                <img src="{{ $user->profile_picture_url }}" class="avatar-img2">
                                            @else
                                                <span class="avatar-initials-text2">
                                                    {{ strtoupper($user->first_name[0] . $user->last_name[0]) }}
                                                </span>
                                            @endif
                                        </div>
                                    </a>

                                    <div class="ms-3">
                                        <a href="{{ route('user.profile', $user->username) }}" class="text-primary-500 fw-semibold d-flex">
                                            {{ $user->username }}
                                        </a>
                                        <div class="small text-primary-300">{{ $user->followers_count }} Followers</div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- LATEST USERS -->
                    <div class="card bg-black-200 shadow rounded-lg p-4 mb-4">
                        <h4 class="fw-bold text-primary-300 d-flex justify-content-start mb-3">New Coming</h4>

                        <ul class="list-unstyled">
                            @foreach ($latestUsers as $user)
                                <li class="mb-3 d-flex justify-content-start">
                                    <a href="{{ route('user.profile', $user->username) }}" class="d-flex align-items-center">
                                        <div class="avatar-circle">
                                            @if($user->profile_picture_url)
                                                <img src="{{ $user->profile_picture_url }}" class="avatar-img2">
                                            @else
                                                <span class="avatar-initials-text2">
                                                    {{ strtoupper($user->first_name[0] . $user->last_name[0]) }}
                                                </span>
                                            @endif
                                        </div>
                                    </a>

                                    <div class="ms-3">
                                        <a href="{{ route('user.profile', $user->username) }}" class="text-primary-500 fw-semibold d-flex">
                                            {{ $user->username }}
                                        </a>
                                        <div class="small text-primary-300">
                                            Joined on {{ $user->created_at->format('M d, Y') }}
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
                <div class="card mb-3 bg-black-200 shadow rounded-lg p-4">

                    <!-- Header -->
                    <div class="row">
                        <div class="col-6">
                            <div class="text-primary-500 small">
                                {{ \Carbon\Carbon::parse($project->upload_date)->format('M d, Y') }}
                            </div>
                            <h3 class="fw-semibold text-primary-300 mb-0">{{ $project->project_name }}</h3>
                            <p class="text-white-400">
                                <a href="{{ route('user.profile', $project->user->username) }}"
                                   class="text-primary-500">
                                    {{ $project->user->username }}
                                </a>
                            </p>
                        </div>

                        <div class="col-6 text-end">
                            <div class="d-flex justify-content-end">
                                <div class="d-flex flex-column pe-3">
                                    <span class="small text-primary-500">Time</span>
                                    <span class="fw-semibold fs-5 text-primary-500">
                                        {{ gmdate('H:i:s', $project->projectDetails->video_duration ?? 0) }}
                                    </span>
                                </div>

                                <div class="d-flex flex-column border-start ps-3">
                                    <span class="small text-primary-500">Major Movement</span>
                                    <span class="fw-semibold fs-5 text-primary-500">
                                        {{ $project->major_movement ?? 'Balanced' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Details -->
                    <div class="row mt-4">

                    <!-- LEFT SPIDER CHART -->
                    <div class="col-md-6 d-flex align-items-stretch">
                        <div class="w-100 p-3 rounded bg-black-200 d-flex justify-content-center align-items-center"
                            style="height:260px; min-height:260px;">
                            <canvas id="spiderChart{{ $project->id }}"></canvas>
                        </div>
                    </div>

                    <!-- RIGHT THUMBNAIL -->
                    <div class="col-md-6 d-flex align-items-stretch">
                        <div class="w-100 p-3 rounded bg-black-200 d-flex justify-content-center align-items-center"
                            style="height:260px; min-height:260px;">
                            <img src="{{ $project->link_image_thumbnail }}"
                                class="img-fluid rounded"
                                style="max-height:100%; object-fit:contain;">
                        </div>
                    </div>

                    <!-- HEATMAP BLOCK -->
                   <div class="col-12 mt-4 d-flex justify-content-center">
    <div style="
        width: 100%;
        max-width: 700px;
        aspect-ratio: 16 / 9;     /* setelah rotate */
        overflow: hidden;
        display: flex;
        justify-content: center;
        align-items: center;
    ">
        <img src="{{ $project->link_image_heatmap_player }}"
            style="
                width: 100%;
                height: 100%;
                object-fit: contain;
                transform: rotate(90deg);
            "
            class="rounded">
    </div>
</div>

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
