<div class="col">
    <a href="{{ route('analytics.show', $project->id) }}" class="card-link text-decoration-none">
        <div class="project-item d-flex flex-row align-items-center p-3 rounded-4">
            <div class="project-thumbnail me-3">
                @if ($project->link_image_thumbnail)
                    <img src="{{ $project->link_image_thumbnail }}" class="img-fluid rounded">
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
