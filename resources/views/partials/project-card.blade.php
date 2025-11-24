<div class="col">
    <a href="{{ route('analytics.show', $project->id) }}" class="card-link text-decoration-none">
        <div class="analytics-card p-4 h-100 d-flex align-items-center">
            <div class="project-thumbnail me-4 position-relative">
                @if ($project->link_image_thumbnail)
                    <img src="{{ $project->link_image_thumbnail }}" class="img-fluid rounded-3 shadow-sm" style="width: 120px; height: 80px; object-fit: cover;">
                    <div class="position-absolute top-50 start-50 translate-middle opacity-0 hover-opacity-100 transition-opacity">
                        <i class="bi bi-play-circle-fill fs-2 text-white drop-shadow"></i>
                    </div>
                @else
                    <div class="d-flex align-items-center justify-content-center bg-dark rounded-3" style="width: 120px; height: 80px;">
                        <i class="bi bi-camera-video fs-2 text-white-50"></i>
                    </div>
                @endif
            </div>

            <div class="flex-grow-1 text-start overflow-hidden">
                <h5 class="fw-bold text-white mb-2 text-truncate">{{ $project->project_name }}</h5>
                <div class="d-flex align-items-center gap-3 mb-2">
                    <small class="text-white-50 d-flex align-items-center">
                        <i class="bi bi-calendar3 me-2"></i>
                        {{ \Carbon\Carbon::parse($project->upload_date)->timezone('Asia/Jakarta')->format('d M Y, H:i') }}
                    </small>
                </div>
                
                <div>
                    @if($project->is_mailed)
                        <span class="badge bg-primary-300 text-black rounded-pill px-3 py-1 fw-bold">
                            <i class="bi bi-check-circle-fill me-1"></i> Analysis Done
                        </span>
                    @else
                        <span class="badge bg-warning text-black rounded-pill px-3 py-1 fw-bold">
                            <i class="bi bi-hourglass-split me-1"></i> Processing...
                        </span>
                    @endif
                </div>
            </div>
            
            <div class="ms-3 text-white-50">
                <i class="bi bi-chevron-right fs-4"></i>
            </div>
        </div>
    </a>
</div>
