<div class="container py-5 text-white">
    <h2 class="fw-bold text-primary-500 mb-2 text-center">Performance Analytics</h2>

    <div class="d-flex flex-wrap justify-content-center align-items-center gap-3 mb-4">
        <span class="badge bg-primary-300 text-black px-3 py-2 fw-semibold">Plan: {{ $planLabel }}</span>
        <span class="badge bg-black-300 border border-primary-300 px-3 py-2">
            @php $remaining = max(0, (int)$maxLimit - (int)$projectCount); @endphp
            Quota: {{ $projectCount }} / {{ $maxLimit }}
            @if ($remaining <= 0)
                • <span class="text-danger fw-semibold">Limit reached</span>
            @else
                • Remaining: {{ $remaining }}
            @endif
        </span>
        <span class="badge bg-black-300 border border-primary-300 px-3 py-2">
            Max file: {{ $maxUploadMb }} MB
        </span>
    </div>

    {{-- Statistik & list project --}}
    <div class="row text-center mb-5 g-4 align-items-center justify-content-center">
        <div class="col-md-4 d-flex flex-column align-items-center">
            <div class="gauge-container mb-3" data-value="{{ $percentageUsed }}">
                <svg viewBox="0 0 100 50" width="160" height="80">
                    <path d="M10,50 A40,40 0 0,1 90,50" class="gauge-bg" />
                    <path id="gauge-arc" d="M10,50 A40,40 0 0,1 90,50"
                          style="transition: stroke-dasharray 1s ease-out"
                          stroke-dasharray="{{ ($percentageUsed/100)*251.2 }} 251.2"
                          stroke-linecap="round" />
                </svg>
                <div class="gauge-label">
                    <span class="fs-2 fw-bold text-primary-300">{{ $projectCount }}</span>
                    <span class="fs-5 text-white-300">/{{ $maxLimit }}</span>
                </div>
            </div>
            <p class="fw-semibold text-white-400 mb-1">Monthly Limit Video</p>
            <small class="text-white-300">Used: {{ $projectCount }} videos</small>
        </div>

        <div class="col-md-4">
            <p class="fs-1 fw-bold text-primary-300 mb-1">{{ $videoInProcessCount }}</p>
            <p class="fw-semibold text-white-400 mb-1">Video In Process</p>
            <small class="text-white-300">Currently being analyzed by AI</small>
        </div>

        <div class="col-md-4">
            <p class="fs-1 fw-bold text-primary-300 mb-1">{{ $videoDoneCount }}</p>
            <p class="fw-semibold text-white-400 mb-1">Video Done</p>
            <small class="text-white-300">Analysis reports available</small>
        </div>
    </div>

    <hr class="divider my-5">

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-5 gap-3">
        <h3 class="fw-bold text-primary-500 mb-0">Your Projects</h3>

        <div class="d-flex align-items-center gap-3 flex-wrap justify-content-end">
           <input type="text" id="searchInput" class="form-control w-auto" placeholder="Search..." value="{{ $search }}">
            <select id="sortSelect" class="form-select rounded-pill w-auto">
                <option value="newest" {{ $sort == 'newest' ? 'selected' : '' }}>Newest</option>
                <option value="oldest" {{ $sort == 'oldest' ? 'selected' : '' }}>Oldest</option>
                <option value="done" {{ $sort == 'done' ? 'selected' : '' }}>Done</option>
                <option value="inprocess" {{ $sort == 'inprocess' ? 'selected' : '' }}>In Process</option>
            </select>

        </div>
    </div>

    <div wire:loading.flex class="justify-content-center align-items-center py-4">
        <div class="spinner-border text-primary" role="status"></div>
    </div>

   <div id="projectsContainer" class="row row-cols-1 row-cols-md-2 g-4">
        @foreach($projects as $project)
            @include('partials.project-card', ['project' => $project])
        @endforeach
    </div>
</div>
