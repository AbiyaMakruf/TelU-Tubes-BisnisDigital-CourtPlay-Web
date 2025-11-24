<div class="container py-5 text-white">
    <div class="text-center mb-5">
        <h2 class="display-5 fw-bold text-primary-500 mb-3">Performance Analytics</h2>
        <div class="d-flex flex-wrap justify-content-center align-items-center gap-3">
            <span class="badge bg-primary-300 text-black px-4 py-2 rounded-pill fw-bold shadow-sm">
                <i class="bi bi-star-fill me-1"></i> Plan: {{ $planLabel }}
            </span>
            <span class="badge bg-dark border border-secondary px-4 py-2 rounded-pill text-white-300">
                @php $remaining = max(0, (int)$maxLimit - (int)$projectCount); @endphp
                <i class="bi bi-pie-chart-fill me-1"></i> Quota: {{ $projectCount }} / {{ $maxLimit }}
                @if ($remaining <= 0)
                    <span class="text-danger ms-1 fw-bold">• Limit reached</span>
                @else
                    <span class="text-success ms-1">• {{ $remaining }} left</span>
                @endif
            </span>
            <span class="badge bg-dark border border-secondary px-4 py-2 rounded-pill text-white-300">
                <i class="bi bi-file-earmark-play-fill me-1"></i> Max file: {{ $maxUploadMb }} MB
            </span>
        </div>
    </div>

    {{-- Statistik & list project --}}
    <div class="row mb-5 g-4">
        <!-- Gauge Card -->
        <div class="col-md-4">
            <div class="stat-card d-flex flex-column align-items-center justify-content-center text-center">
                <div class="gauge-container mb-3 position-relative" data-value="{{ $percentageUsed }}">
                    <svg viewBox="0 0 100 50" width="180" height="90">
                        <path d="M10,50 A40,40 0 0,1 90,50" class="gauge-bg" />
                        <path id="gauge-arc" d="M10,50 A40,40 0 0,1 90,50"
                              style="transition: stroke-dasharray 1s ease-out"
                              stroke-dasharray="{{ ($percentageUsed/100)*251.2 }} 251.2" />
                    </svg>
                    <div class="position-absolute top-100 start-50 translate-middle text-center" style="margin-top: -20px;">
                        <div class="fs-2 fw-bold text-primary-300 lh-1">{{ $projectCount }}</div>
                        <div class="small text-white-300">/{{ $maxLimit }}</div>
                    </div>
                </div>
                <h5 class="fw-bold text-white mb-1 mt-3">Monthly Usage</h5>
                <small class="text-white-50 opacity-75">Videos uploaded this month</small>
            </div>
        </div>

        <!-- In Process Card -->
        <div class="col-md-4">
            <div class="stat-card d-flex flex-column align-items-center justify-content-center text-center">
                <div class="mb-3 p-3 rounded-circle bg-warning bg-opacity-10 text-warning">
                    <i class="bi bi-hourglass-split fs-1"></i>
                </div>
                <div class="fs-1 fw-bold text-white mb-0">{{ $videoInProcessCount }}</div>
                <h5 class="fw-bold text-white-400 mb-1">Processing</h5>
                <small class="text-white-50 opacity-75">Currently being analyzed by AI</small>
            </div>
        </div>

        <!-- Done Card -->
        <div class="col-md-4">
            <div class="stat-card d-flex flex-column align-items-center justify-content-center text-center">
                <div class="mb-3 p-3 rounded-circle bg-primary bg-opacity-10 text-primary-300">
                    <i class="bi bi-check-circle-fill fs-1"></i>
                </div>
                <div class="fs-1 fw-bold text-white mb-0">{{ $videoDoneCount }}</div>
                <h5 class="fw-bold text-white-400 mb-1">Completed</h5>
                <small class="text-white-50 opacity-75">Analysis reports ready to view</small>
            </div>
        </div>
    </div>

    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <h3 class="fw-bold text-white mb-0 d-flex align-items-center">
            <i class="bi bi-collection-play me-2 text-primary-300"></i> Your Projects
        </h3>

        <div class="d-flex align-items-center gap-3 flex-wrap">
            <div class="position-relative">
                <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-white-50"></i>
                <input type="text" id="searchInput" class="form-control form-control-modern ps-5" placeholder="Search projects..." value="{{ $search }}">
            </div>
            <select id="sortSelect" class="form-select form-select-modern cursor-pointer">
                <option value="newest" {{ $sort == 'newest' ? 'selected' : '' }}>Newest First</option>
                <option value="oldest" {{ $sort == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                <option value="done" {{ $sort == 'done' ? 'selected' : '' }}>Completed</option>
                <option value="inprocess" {{ $sort == 'inprocess' ? 'selected' : '' }}>Processing</option>
            </select>
        </div>
    </div>

    <div wire:loading.flex class="justify-content-center align-items-center py-5">
        <div class="spinner-border text-primary-300" role="status" style="width: 3rem; height: 3rem;"></div>
    </div>

   <div id="projectsContainer" class="row row-cols-1 row-cols-md-2 g-4">
        @foreach($projects as $project)
            @include('partials.project-card', ['project' => $project])
        @endforeach
    </div>
</div>
