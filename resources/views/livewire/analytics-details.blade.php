<div class="container py-5 text-white">
    <!-- Header Section -->
    <div class="text-center mb-5">
        <h1 class="display-5 fw-bold text-white mb-3">{{ $project->project_name }}</h1>
        <div class="d-flex flex-wrap justify-content-center gap-3">
            <div class="meta-badge">
                <i class="bi bi-calendar3"></i>
                {{ \Carbon\Carbon::parse($project->upload_date)->format('d M Y') }}
            </div>
            <div class="meta-badge">
                <i class="bi bi-clock"></i>
                Duration: {{ $videoDuration }}
            </div>
            <div class="meta-badge">
                <i class="bi bi-cpu"></i>
                Processed in: {{ $processingTime }}
            </div>
        </div>
    </div>

    <!-- Main Video Section -->
    <div class="row g-4 mb-5">
        <!-- Object Detection Video -->
        <div class="col-12">
            <div class="glass-card h-100">
                <div class="glass-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-white"><i class="bi bi-crosshair me-2 text-primary-300"></i>Player & Ball Tracking</h5>
                </div>
                <div class="p-3">
                    @if ($video_object_detection_Url != "IN DEVELOPMENT" && $video_object_detection_Url != null)
                        <div class="video-container">
                            <video controls autoplay loop muted playsinline class="w-100 d-block" style="max-height: 500px; object-fit: contain;">
                                <source src="{{ $video_object_detection_Url }}" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        </div>
                    @else
                        <div class="placeholder-modern d-flex align-items-center justify-content-center" style="height: 400px;">
                            <div class="text-center">
                                <div class="spinner-border text-primary-300 mb-3" role="status"></div>
                                <p class="fw-semibold text-white-400 mb-0">Processing video analysis...</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Skeletal & Stats Section -->
    <div class="row g-4 mb-5">
        <!-- Skeletal Analysis -->
        <div class="col-lg-8">
            <div class="glass-card h-100">
                <div class="glass-header">
                    <h5 class="mb-0 fw-bold text-white"><i class="bi bi-person-lines-fill me-2 text-primary-300"></i>Skeletal Analysis</h5>
                </div>
                <div class="p-3">
                    @if ($video_player_keypoints_Url != "IN DEVELOPMENT" && $video_player_keypoints_Url != null)
                        <div class="video-container">
                            <video controls autoplay loop muted playsinline class="w-100 d-block" style="max-height: 500px; object-fit: contain;">
                                <source src="{{ $video_player_keypoints_Url }}" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        </div>
                    @else
                        <div class="placeholder-modern d-flex align-items-center justify-content-center" style="height: 400px;">
                            <div class="text-center">
                                <div class="spinner-border text-primary-300 mb-3" role="status"></div>
                                <p class="fw-semibold text-white-400 mb-0">Generating skeletal data...</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Stats Panel -->
        <div class="col-lg-4">
            <div class="glass-card h-100">
                <div class="glass-header">
                    <h5 class="mb-0 fw-bold text-white"><i class="bi bi-bar-chart-fill me-2 text-primary-300"></i>Shot Statistics</h5>
                </div>
                <div class="p-4 d-flex flex-column justify-content-center h-100">
                    <div class="stat-row">
                        <div class="d-flex justify-content-between align-items-end mb-1">
                            <span class="text-white-400 fw-medium">Forehand</span>
                            <span class="text-primary-300 fw-bold fs-5">{{ $forehand }}</span>
                        </div>
                        <div class="progress-modern">
                            <div class="progress-bar-modern" style="width: {{ ($forehand / $maxValue) * 100 }}%;"></div>
                        </div>
                    </div>

                    <div class="stat-row">
                        <div class="d-flex justify-content-between align-items-end mb-1">
                            <span class="text-white-400 fw-medium">Backhand</span>
                            <span class="text-primary-300 fw-bold fs-5">{{ $backhand }}</span>
                        </div>
                        <div class="progress-modern">
                            <div class="progress-bar-modern" style="width: {{ ($backhand / $maxValue) * 100 }}%;"></div>
                        </div>
                    </div>

                    <div class="stat-row">
                        <div class="d-flex justify-content-between align-items-end mb-1">
                            <span class="text-white-400 fw-medium">Serve</span>
                            <span class="text-primary-300 fw-bold fs-5">{{ $serve }}</span>
                        </div>
                        <div class="progress-modern">
                            <div class="progress-bar-modern" style="width: {{ ($serve / $maxValue) * 100 }}%;"></div>
                        </div>
                    </div>

                    <div class="stat-row mb-0">
                        <div class="d-flex justify-content-between align-items-end mb-1">
                            <span class="text-white-400 fw-medium">Ready Position</span>
                            <span class="text-primary-300 fw-bold fs-5">{{ $ready }}</span>
                        </div>
                        <div class="progress-modern">
                            <div class="progress-bar-modern" style="width: {{ ($ready / $maxValue) * 100 }}%;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Heatmap Section -->
    <div class="row g-4 mb-5">
        <div class="col-12">
            <h3 class="fw-bold text-white mb-4 ps-2 border-start border-4 border-primary-300">Movement Analysis</h3>
        </div>
        
        <!-- Video/Minimap Column -->
        <div class="col-lg-3">
            <div class="glass-card h-100 d-flex flex-column">
                <div class="glass-header d-flex justify-content-between align-items-center py-2">
                    <span class="fw-bold text-white small">Visual</span>
                    <div class="d-flex align-items-center gap-2">
                        <span class="small text-white-50">Map</span>
                        <label class="toggle-switch scale-75">
                            <input type="checkbox" wire:model.live="isHeatmap" @checked($videoTab === 'heatmapvideo')>
                            <span class="slider"></span>
                        </label>
                        <span class="small text-white-50">Heat</span>
                    </div>
                </div>
                <div class="p-2 flex-grow-1 d-flex flex-column">
                    <div class="video-container bg-black flex-grow-1 position-relative rounded-3 overflow-hidden" style="min-height: 300px;">
                        @if($videoTab === 'minimap')
                            @if ($minimapUrl)
                                <video wire:key="minimap-{{ $minimapUrl }}" controls autoplay loop muted playsinline class="w-100 h-100 position-absolute top-0 start-0" style="object-fit: contain;">
                                    <source src="{{ $minimapUrl }}" type="video/mp4">
                                </video>
                            @else
                                <div class="d-flex align-items-center justify-content-center h-100 w-100 position-absolute top-0 start-0">
                                    <span class="text-white-50 small">Processing...</span>
                                </div>
                            @endif
                        @else
                            @if ($videoHeatmapUrl)
                                <video wire:key="heatmap-{{ $videoHeatmapUrl }}" controls autoplay loop muted playsinline class="w-100 h-100 position-absolute top-0 start-0" style="object-fit: contain;">
                                    <source src="{{ $videoHeatmapUrl }}" type="video/mp4">
                                </video>
                            @else
                                <div class="d-flex align-items-center justify-content-center h-100 w-100 position-absolute top-0 start-0">
                                    <span class="text-white-50 small">Processing...</span>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Image Column -->
        <div class="col-lg-3">
            <div class="glass-card h-100 d-flex flex-column">
                <div class="glass-header py-2 text-center">
                    <span class="fw-bold text-white small">Heatmap Image</span>
                </div>
                <div class="p-2 flex-grow-1 d-flex flex-column">
                    <div class="video-container bg-black flex-grow-1 position-relative rounded-3 overflow-hidden" style="min-height: 300px;">
                        @if ($imageHeatmapUrl)
                            <img src="{{ $imageHeatmapUrl }}" class="w-100 h-100 position-absolute top-0 start-0" style="object-fit: contain;">
                        @else
                            <div class="d-flex align-items-center justify-content-center h-100 w-100 position-absolute top-0 start-0">
                                <span class="text-white-50 small">Processing...</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- AI Insight Column -->
        <div class="col-lg-6">
            <div class="ai-insight-box h-100 p-4">
                <div class="d-flex align-items-center mb-3">
                    <i class="bi bi-stars me-2 text-primary-300 fs-4"></i>
                    <h5 class="fw-bold text-white mb-0">AI Movement Insight</h5>
                </div>
                <div class="text-white-400 lh-lg">
                    @if ($text_heatmap)
                        {{ $text_heatmap }}
                    @else
                        <div class="d-flex flex-column align-items-center justify-content-center py-5 opacity-50">
                            <div class="spinner-grow text-primary-300 mb-3" role="status"></div>
                            <span>Analyzing player movement patterns...</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Ball Drop Section -->
    <div class="row g-4">
        <div class="col-12">
            <h3 class="fw-bold text-white mb-4 ps-2 border-start border-4 border-primary-300">Ball Placement Analysis</h3>
        </div>

        <!-- Video Column -->
        <div class="col-lg-3">
            <div class="glass-card h-100 d-flex flex-column">
                <div class="glass-header py-2 text-center">
                    <span class="fw-bold text-white small">Ball Tracking</span>
                </div>
                <div class="p-2 flex-grow-1 d-flex flex-column">
                    <div class="video-container bg-black flex-grow-1 position-relative rounded-3 overflow-hidden" style="min-height: 300px;">
                        @if ($videoBalldroppingsUrl)
                            <video controls autoplay loop muted playsinline class="w-100 h-100 position-absolute top-0 start-0" style="object-fit: contain;">
                                <source src="{{ $videoBalldroppingsUrl }}" type="video/mp4">
                            </video>
                        @else
                            <div class="d-flex align-items-center justify-content-center h-100 w-100 position-absolute top-0 start-0">
                                <span class="text-white-50 small">Processing...</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Image Switch Column -->
        <div class="col-lg-3">
            <div class="glass-card h-100 d-flex flex-column">
                <div class="glass-header d-flex justify-content-between align-items-center py-2">
                    <span class="fw-bold text-white small">Distribution</span>
                    <div class="d-flex align-items-center gap-2">
                        <span class="small text-white-50">Map</span>
                        <label class="toggle-switch scale-75">
                            <input type="checkbox" wire:model.live="isBallHeatmap" @checked($ballTab === 'heatmap')>
                            <span class="slider"></span>
                        </label>
                        <span class="small text-white-50">Heat</span>
                    </div>
                </div>
                <div class="p-2 flex-grow-1 d-flex flex-column">
                    <div class="video-container bg-black flex-grow-1 position-relative rounded-3 overflow-hidden" style="min-height: 300px;">
                        @if($ballTab==='minimap')
                            @if ($balldropUrl)
                                <img wire:key="ball-minimap-{{ $balldropUrl }}" src="{{ $balldropUrl }}" class="w-100 h-100 position-absolute top-0 start-0" style="object-fit: contain;">
                            @else
                                <div class="d-flex align-items-center justify-content-center h-100 w-100 position-absolute top-0 start-0">
                                    <span class="text-white-50 small">Processing...</span>
                                </div>
                            @endif
                        @else
                            @if ($imageHeatmapBalldroppingsUrl)
                                <img wire:key="ball-heatmap-{{ $imageHeatmapBalldroppingsUrl }}" src="{{ $imageHeatmapBalldroppingsUrl }}" class="w-100 h-100 position-absolute top-0 start-0" style="object-fit: contain;">
                            @else
                                <div class="d-flex align-items-center justify-content-center h-100 w-100 position-absolute top-0 start-0">
                                    <span class="text-white-50 small">Processing...</span>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- AI Insight Column -->
        <div class="col-lg-6">
            <div class="ai-insight-box h-100 p-4">
                <div class="d-flex align-items-center mb-3">
                    <i class="bi bi-stars me-2 text-primary-300 fs-4"></i>
                    <h5 class="fw-bold text-white mb-0">AI Ball Drop Insight</h5>
                </div>
                <div class="text-white-400 lh-lg">
                    @if ($text_balldrop)
                        {{ $text_balldrop }}
                    @else
                        <div class="d-flex flex-column align-items-center justify-content-center py-5 opacity-50">
                            <div class="spinner-grow text-primary-300 mb-3" role="status"></div>
                            <span>Analyzing shot placement...</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
