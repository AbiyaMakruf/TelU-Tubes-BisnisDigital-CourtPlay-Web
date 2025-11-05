<div class="container py-5 text-white">
    <h2 class="fw-bold text-primary-500 mb-2 text-center">Performance Analytics</h2>

    <div class="d-flex flex-wrap justify-content-center align-items-center gap-3 mb-4">
        <span class="badge bg-primary-300 text-black px-3 py-2 fw-semibold">Plan: {{ $planLabel }}</span>
        <span class="badge bg-black-300 border border-primary-300 px-3 py-2">
            Quota: {{ $projectCount }} / {{ $maxLimit }}
            @if($remaining===0)
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
            <input type="text" wire:model.debounce.500ms="search" class="form-control w-auto" placeholder="Search...">
            <select wire:model="sort" class="form-select rounded-pill w-auto">
                <option value="newest">Newest</option>
                <option value="oldest">Oldest</option>
                <option value="done">Done</option>
                <option value="inprocess">In Process</option>
            </select>
        </div>
    </div>

    <div wire:loading.flex class="justify-content-center align-items-center py-4">
        <div class="spinner-border text-primary" role="status"></div>
    </div>

    <div class="row row-cols-1 row-cols-md-2 g-4" wire:loading.remove>
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
