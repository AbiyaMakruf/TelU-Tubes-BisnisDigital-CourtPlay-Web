@extends('layouts.app-auth')

@section('title', 'Analytics Dashboard')
@section('fullbleed', true)

@section('content')
<div class="container py-5 text-white">

    {{-- === TITLE === --}}
    <h2 class="fw-bold text-primary-500 mb-5 text-center">Performance Analytics</h2>

    {{-- === TOP METRICS === --}}
    <div class="row text-center mb-5 g-4 align-items-center justify-content-center">

        {{-- Monthly Limit Gauge --}}
        <div class="col-md-4 d-flex flex-column align-items-center">
            <div class="gauge-container mb-3" data-value="{{ $percentageUsed }}">
                <svg viewBox="0 0 100 50" width="160" height="80">
                    <path d="M10,50 A40,40 0 0,1 90,50" class="gauge-bg" />
                    <path id="gauge-arc" d="M10,50 A40,40 0 0,1 90,50"
                        stroke-dasharray="0 251.2" stroke-linecap="round" />
                </svg>
                <div class="gauge-label">
                    <span class="fs-2 fw-bold text-primary-300">{{ $projectCount }}</span>
                    <span class="fs-5 text-white-300">/{{ $maxLimit }}</span>
                </div>
            </div>
            <p class="fw-semibold text-white-400 mb-1">Monthly Limit Video</p>
            <small class="text-white-300">Used: {{ $projectCount }} videos</small>
        </div>

        {{-- Video In Process --}}
        <div class="col-md-4">
            <p class="fs-1 fw-bold text-primary-300 mb-1">{{ $videoInProcessCount }}</p>
            <p class="fw-semibold text-white-400 mb-1">Video In Process</p>
            <small class="text-white-300">Currently being analyzed by AI</small>
        </div>

        {{-- Video Done --}}
        <div class="col-md-4">
            <p class="fs-1 fw-bold text-primary-300 mb-1">{{ $videoDoneCount }}</p>
            <p class="fw-semibold text-white-400 mb-1">Video Done</p>
            <small class="text-white-300">Analysis reports available</small>
        </div>
    </div>

    <hr class="divider my-5">

    {{-- === FILTER BAR === --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-5 gap-3">
        <h3 class="fw-bold text-primary-500 mb-0">Your Projects</h3>

        <form id="filterForm" method="GET" action="{{ route('analytics') }}"
              class="d-flex align-items-center gap-3 flex-wrap justify-content-end">

            {{-- Search --}}
            <div class="search-wrapper position-relative">
                <input type="text" name="search" id="searchInput"
                    class="form-control search-input"
                    placeholder="Search..."
                    value="{{ $currentSearch ?? '' }}">
                <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-black"></i>
            </div>

            {{-- Sort Dropdown --}}
            <div class="dropdown dropdown-custom">
                <button class="btn btn-dropdown rounded-pill dropdown-toggle"
                        type="button" id="sortDropdownMenuButton" data-bs-toggle="dropdown"
                        aria-expanded="false">
                    <span id="currentSortLabel">
                        @switch($currentSort ?? 'newest')
                            @case('oldest') Oldest @break
                            @case('done') Done @break
                            @case('inprocess') In Process @break
                            @default Newest
                        @endswitch
                    </span>
                </button>
                <ul class="dropdown-menu p-2 rounded-4" aria-labelledby="sortDropdownMenuButton" id="customSortMenu">
                    <li>
                        <a class="dropdown-item custom-option mb-2 @if (($currentSort ?? 'newest') === 'newest') active @endif"
                        href="#" data-value="newest">Newest</a>
                    </li>
                    <li>
                        <a class="dropdown-item custom-option mb-2 @if (($currentSort ?? 'newest') === 'oldest') active @endif"
                        href="#" data-value="oldest">Oldest</a>
                    </li>
                    <li>
                        <a class="dropdown-item custom-option mb-2 @if (($currentSort ?? 'newest') === 'done') active @endif"
                        href="#" data-value="done">Done</a>
                    </li>
                    <li>
                        <a class="dropdown-item custom-option mb-2 @if (($currentSort ?? 'newest') === 'inprocess') active @endif"
                        href="#" data-value="inprocess">In Process</a>
                    </li>
                </ul>

            </div>
        </form>
    </div>

    {{-- === PROJECT LIST === --}}
    <div class="row row-cols-1 row-cols-md-2 g-4">
        @forelse ($projects as $project)
            <div class="col">
                <a href="{{ route('analytics.show', $project->id) }}" class="card-link text-decoration-none">
                    <div class="project-item d-flex flex-row align-items-center p-3 rounded-4">
                        <div class="project-thumbnail me-3">
                            @if ($project->link_image_thumbnail)
                                <img src="{{ $project->link_image_thumbnail }}" alt="Thumbnail" class="img-fluid rounded">
                            @else
                                <i class="bi bi-camera-video fs-1 text-primary-300"></i>
                            @endif
                        </div>
                        <div class="flex-grow-1 text-start">
                            <h5 class="fw-bold text-primary-500 mb-1">{{ $project->project_name }}</h5>
                            <p class="text-white-300 small mb-1">
                                Uploaded: {{ \Carbon\Carbon::parse($project->upload_date)->timezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB
                            </p>
                            <span class="badge {{ $project->is_mailed ? 'bg-primary-300 text-black' : 'bg-warning text-black' }} fw-semibold">
                                {{ $project->is_mailed ? 'Analysis Done' : 'Processing...' }}
                            </span>
                        </div>
                    </div>
                </a>
            </div>
        @empty
            <div class="col-12 text-center text-white-400 py-5">
                <p>Let’s upload a video!</p>
            </div>
        @endforelse
    </div>
</div>

{{-- === SCRIPT === --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
    const gaugeArc = document.getElementById('gauge-arc');
    const maxLength = 251.2;
    const dashLength = (parseFloat("{{ $percentageUsed }}") / 100) * maxLength;
    gaugeArc.style.transition = 'stroke-dasharray 1s ease-out';
    gaugeArc.setAttribute('stroke-dasharray', `${dashLength} ${maxLength}`);

    // Dropdown sort logic
    const filterForm = document.getElementById('filterForm');
    const customMenu = document.getElementById('customSortMenu');
    const searchInput = document.getElementById('searchInput');
    const currentLabel = document.getElementById('currentSortLabel');

    if (!document.getElementById('hiddenSortInput')) {
        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'sort';
        hiddenInput.id = 'hiddenSortInput';
        hiddenInput.value = "{{ $currentSort ?? 'newest' }}";
        filterForm.appendChild(hiddenInput);
    }

    const hiddenInput = document.getElementById('hiddenSortInput');

    customMenu.querySelectorAll('.custom-option').forEach(option => {
        option.addEventListener('click', e => {
            e.preventDefault();
            hiddenInput.value = option.dataset.value;
            currentLabel.textContent = option.textContent;
            filterForm.submit();
        });
    });

    let searchTimeout;
    searchInput.addEventListener('input', () => {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => filterForm.submit(), 700);
    });
});
</script>
@endsection
