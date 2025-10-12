@extends('layouts.app-auth')

@section('title', 'Analytics Dashboard')
@section('fullbleed', true)

@section('content')
<div class="container py-5 text-white">

    {{-- === TITLE === --}}
    <h2 class="fw-bold text-primary-500 mb-5 text-center ">Performance Analytics</h2>

    {{-- === TOP METRICS (GAUGE + 2 TEXT BLOCKS) === --}}
    <div class="row text-center mb-5 g-4 align-items-center justify-content-center">

        {{-- Monthly Limit Gauge --}}
        <div class="col-md-4 d-flex flex-column align-items-center">
            <div class="gauge-container mb-3" data-value="{{ $percentageUsed }}">
                <svg viewBox="0 0 100 50" width="160" height="80">
                    <path d="M10,50 A40,40 0 0,1 90,50" stroke="var(--primary-500)" stroke-width="7" fill="none" />
                    <path id="gauge-arc" d="M10,50 A40,40 0 0,1 90,50"
                        stroke="var(--primary-300)" stroke-width="10" fill="none"
                        stroke-dasharray="0 251.2" stroke-linecap="round" />
                </svg>
                <div class="gauge-label">
                    <span class="fs-2 fw-bold text-primary-300">{{ $projectCount }}</span>
                    <span class="fs-5 text-white-300">/{{ $maxLimit  }}</span>
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

    <hr class="border-white-300 my-5">

    {{-- === FILTER BAR === --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-5 gap-3">

        <h3 class="fw-bold text-primary-500 mb-0">Your Projects</h3>

        <form id="filterForm" method="GET" action="{{ route('analytics') }}"
              class="d-flex align-items-center gap-3 flex-wrap justify-content-end">

            {{-- Search --}}
            <div class="search-wrapper position-relative">
                <input type="text" name="search" id="searchInput"
                    class="form-control search-input bg-primary-500 text-black ps-5"
                    placeholder="Search..."
                    value="{{ $currentSearch ?? '' }}">
                <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-black"></i>
            </div>

            {{-- Sort Dropdown --}}
            <div class="dropdown">
                <button class="btn sort-select-custom dropdown-toggle bg-primary-500 border-0 text-black fw-semibold"
                        type="button" id="sortDropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false"
                        style="width: 150px; border-radius: 50px;">
                    <span id="currentSortLabel">
                        @if (($currentSort ?? 'newest') === 'alphabet')
                            Alphabet
                        @else
                            Newest
                        @endif
                    </span>
                </button>
                <ul class="dropdown-menu dropdown-menu-dark p-2" aria-labelledby="sortDropdownMenuButton"
                    id="customSortMenu" style="min-width: 150px;">
                    <li><a class="dropdown-item custom-option rounded-pill mb-1 @if (($currentSort ?? 'newest') === 'newest') active @endif" href="#" data-value="newest">Newest</a></li>
                    <li><a class="dropdown-item custom-option rounded-pill @if (($currentSort ?? 'newest') === 'alphabet') active @endif" href="#" data-value="alphabet">Alphabet</a></li>
                </ul>
            </div>
        </form>
    </div>

    {{-- === PROJECT LIST === --}}
    <div class="row row-cols-1 row-cols-md-2 g-4">
        @forelse ($projects as $project)
            <div class="col">
                <a href="#" class="card-link text-decoration-none">
                    <div class="project-item d-flex flex-row align-items-center p-3 rounded-4 bg-black-200">
                        <div class="me-3 p-2 rounded bg-black-300 d-flex align-items-center justify-content-center"
                             style="width: 100px; height: 100px;">
                            @if ($project->link_image_thumbnail)
                                <img src="{{ $project->link_image_thumbnail }}" alt="Thumbnail"
                                     class="img-fluid rounded" style="object-fit: cover; height: 100%; width: 100%;">
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
            <div class="col-12 text-center text-white-400 py-5 text-md-start">
                <p>Lets upload a video!</p>
            </div>
        @endforelse
    </div>
</div>

{{-- === STYLES === --}}
<style>
:root {
    --primary-300: #a3ce14;
    --primary-500: #f4fdca;
    --black-200: #1c1c1c;
    --black-300: #292929;
    --white-300: #e4e4e4;
}

/* === GAUGE === */
.gauge-container {
    position: relative;
    display: inline-block;
}
.gauge-label {
    position: absolute;
    top: 70%;
    left: 50%;
    transform: translate(-50%, -50%);
}

/* === PROJECT ITEM === */
.project-item {
    transition: all 0.25s ease;
    border: 1px solid transparent;
}
.project-item:hover {
    border: 1px solid var(--primary-300);
    background-color: #202020;
}

/* === SEARCH === */
.search-input {
    border: none;
    border-radius: 50px;
    height: 38px;
    font-size: 0.9rem;
    transition: all 0.3s ease;
}
.search-input::placeholder {
    color: var(--black-300);
}
.search-input:focus {
    outline: none;
    box-shadow: 0 0 0 2px var(--primary-300);
    background-color: var(--primary-500);
}

.

/* === SORT BUTTON === */
.sort-select-custom {
    border-radius: 50px !important;
    height: 38px;
    transition: all 0.3s ease;
}
.sort-select-custom:hover {
    filter: brightness(0.9);
}

/* RESPONSIVE */
@media (max-width: 768px) {
    .d-flex.flex-wrap.justify-content-between.align-items-center {
        flex-direction: column;
        align-items: stretch;
        gap: 10px;
    }
    #filterForm {
        width: 100%;
        justify-content: space-between;
    }
    .search-wrapper {
        flex-grow: 1;
        width: 100%;
    }
}
</style>

{{-- === SCRIPT === --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
    const percentage = parseFloat("{{ $percentageUsed }}");
    const gaugeArc = document.getElementById('gauge-arc');
    const maxLength = 251.2;
    const dashLength = (percentage / 100) * maxLength;
    gaugeArc.style.transition = 'stroke-dasharray 1s ease-out';
    gaugeArc.setAttribute('stroke-dasharray', `${dashLength} ${maxLength}`);

    // Dropdown logic
    const filterForm = document.getElementById('filterForm');
    const searchInput = document.getElementById('searchInput');
    const customMenu = document.getElementById('customSortMenu');
    const hiddenInput = document.getElementById('hiddenSortInput');
    const currentLabel = document.getElementById('currentSortLabel');

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
