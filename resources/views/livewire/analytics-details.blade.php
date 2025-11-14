<div class="container py-5 text-white ">
    <h1 class="fw-bold text-primary-500 mb-2">{{ $project->project_name }}</h1>
    <p class="mb-1">Date: <span class="text-primary-300">{{ \Carbon\Carbon::parse($project->upload_date)->format('d-m-Y') }}</span></p>
    <p class="mb-1">Video duration: <span class="text-primary-300">{{ $videoDuration }}</span></p>
    <p>Video processing time: <span class="text-primary-300">{{ $processingTime }}</span></p>

    <div class="video-wrapper mb-4 position-relative">
        <h6 class="fw-semibold text-primary-300 mb-3 text-center">Player & Ball Position</h6>
        @if ($video_object_detection_Url != "IN DEVELOPMENT" && $video_object_detection_Url != null)
            <video controls autoplay loop muted playsinline class="w-100 rounded-4 shadow " style="max-height: 480px;">
                <source src="{{ $video_object_detection_Url }}" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        @else
            <div class="placeholder-wrapper rounded-4 bg-black-300 d-flex align-items-center justify-content-center " style="height: 480px;">
                <div class="text-center">
                    <i class="bi bi-camera-video-off text-primary-300 mb-3 d-block" style="font-size: 5rem;"></i>
                    <p class="fw-semibold text-white-400">Video is being processed...</p>
                </div>
            </div>
        @endif
    </div>


    <div class="row g-4 align-items-start mb-4 ">
        <div class="col-md-6">
            <div class="video-wrapper position-relative">
                <h6 class="fw-semibold text-primary-300 mb-3 text-center">Player Keypoint</h6>
                @if ($video_player_keypoints_Url != "IN DEVELOPMENT" && $video_player_keypoints_Url != null)
                    <video controls autoplay loop muted playsinline playsinline  class="w-100 rounded-4 shadow " style="max-height: 480px;">
                        <source src="{{ $video_player_keypoints_Url }}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                @else
                    <div class="placeholder-wrapper rounded-4 bg-black-300 d-flex align-items-center justify-content-center " style="height: 480px;">
                        <div class="text-center">
                            <i class="bi bi-camera-video-off text-primary-300 mb-3 d-block" style="font-size: 5rem;"></i>
                            <p class="fw-semibold text-white-400">Video is being processed...</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <div class="col-md-6">
            <div class="stat-bars" style="margin-top: 2.1rem; margin-bottom: 2rem;">
                <p class="fw-semibold text-white-400 mb-4 d-flex justify-content-between">
                    <span>Forehand</span><span class="text-primary-300">{{ $forehand }}</span>
                </p>
                <div class="bar-bg"><div class="bar-fill" style="width: {{ ($forehand / $maxValue) * 100 }}%;"></div></div>

                <p class="fw-semibold text-white-400 mt-3 mb-1 d-flex justify-content-between">
                    <span>Backhand</span><span class="text-primary-300">{{ $backhand }}</span>
                </p>
                <div class="bar-bg"><div class="bar-fill" style="width: {{ ($backhand / $maxValue) * 100 }}%;"></div></div>

                <p class="fw-semibold text-white-400 mt-3 mb-1 d-flex justify-content-between">
                    <span>Serve</span><span class="text-primary-300">{{ $serve }}</span>
                </p>
                <div class="bar-bg"><div class="bar-fill" style="width: {{ ($serve / $maxValue) * 100 }}%;"></div></div>

                <p class="fw-semibold text-white-400 mt-3 mb-1 d-flex justify-content-between">
                    <span>Ready Position</span><span class="text-primary-300">{{ $ready }}</span>
                </p>
                <div class="bar-bg"><div class="bar-fill" style="width: {{ ($ready / $maxValue) * 100 }}%;"></div></div>
            </div>
        </div>
    </div>

{{-- ===================== HEATMAP PLAYER ===================== --}}
<div class="row g-4 align-items-start mb-4">



    {{-- === VIDEO WRAPPER COLUMN === --}}
    <div class="col-md-3">
        <div class="d-flex justify-content-between mb-2">

            <!-- Label kiri -->
            <div class="ms-2 d-flex align-items-center ">
                <h6 class="fw-semibold text-primary-300 mb-0">Video</h6>
            </div>

            <!-- Toggle + text kanan -->
            <div class="d-flex align-items-center">

                <!-- Minimap text -->
                <span style="font-size: 12px; margin-right: 8px;">Minimap</span>

                <!-- Toggle switch -->
                <label class="toggle-switch" style="cursor:pointer;">
                    <input type="checkbox"
                        wire:model.live="isHeatmap"
                        @checked($videoTab === 'heatmapvideo')>
                    <span class="slider"></span>
                </label>

                <!-- Heatmap text -->
                <span style="font-size: 12px; margin-left: 8px;">Heatmap</span>

            </div>

        </div>




        <div class="video-wrapper position-relative ">

            @if($videoTab === 'minimap')
                @if ($minimapUrl)
                    <video controls autoplay loop muted playsinline
                        class="w-100 rounded-4 shadow placeholder-wrapper"
                        style="height:520px; object-fit:cover;">
                        <source src="{{ $minimapUrl }}" type="video/mp4">
                    </video>
                @else
                    <div class="placeholder-wrapper rounded-4 bg-black-300
                        d-flex align-items-center justify-content-center"
                        style="height:520px;">
                        <p class="fw-semibold text-white-400">Minimap in process...</p>
                    </div>
                @endif
            @endif

            @if($videoTab === 'heatmapvideo')
                @if ($videoHeatmapUrl)
                    <video controls autoplay loop muted playsinline
                        class="w-100 rounded-4 shadow placeholder-wrapper"
                        style="height:520px; object-fit:cover;">
                        <source src="{{ $videoHeatmapUrl }}" type="video/mp4">
                    </video>
                @else
                    <div class="placeholder-wrapper rounded-4 bg-black-300
                        d-flex align-items-center justify-content-center"
                        style="height:520px;">
                        <p class="fw-semibold text-white-400">Heatmap in process...</p>
                    </div>
                @endif
            @endif

        </div>
    </div>

    {{-- === IMAGE WRAPPER COLUMN === --}}
    <div class="col-md-3">
        <div class=" component-custom">
            <h6 class="fw-semibold text-primary-300 text-center">Image</h6>
        </div>


        <div class="image-wrapper position-relative w-100">

            @if ($imageHeatmapUrl)
                <img src="{{ $imageHeatmapUrl }}"
                    class="w-100 rounded-4 shadow placeholder-wrapper"
                    style="height:520px; object-fit:cover;">
            @else
                <div class="placeholder-wrapper rounded-4 bg-black-300
                    d-flex align-items-center justify-content-center w-100"
                    style="height:520px;">
                    <p class="fw-semibold text-white-400">Image in process...</p>
                </div>
            @endif

        </div>
    </div>



    {{-- === AI TEXT === --}}
    <div class="col-md-6">
        <h6 class="fw-semibold text-primary-300 fw-bold mb-2 text-center">
            <i class="bi bi-stars me-2"></i>Insight
        </h6>

        <div class="p-3 bg-black-200 rounded-4 shadow-sm ai-box  @if(strlen($text_heatmap ?? '') < 600) auto-fit @endif">
            @if ($text_heatmap)
                <p class="text-white-400 mb-0">{{ $text_heatmap }}</p>
            @else
                <p class="text-white-400 text-center mt-5">Insight in process...</p>
            @endif
        </div>
    </div>

</div>



{{-- ===================== BALL DROP ===================== --}}
<div class="row g-4 align-items-start mb-4">

    {{-- === VIDEO STATIC WRAPPER === --}}
    <div class="col-md-3">
        <h6 class="fw-semibold text-primary-300 component-custom text-center">Video</h6>

        <div class="video-wrapper position-relative">

            @if ($videoBalldroppingsUrl)
                <video controls autoplay loop muted playsinline
                    class="w-100 rounded-4 shadow placeholder-wrapper"
                    style="height:520px; object-fit:cover;">
                    <source src="{{ $videoBalldroppingsUrl }}" type="video/mp4">
                </video>
            @else
                <div class="placeholder-wrapper rounded-4 bg-black-300
                    d-flex align-items-center justify-content-center"
                    style="height:520px;">
                    <p class="fw-semibold text-white-400">Video in process...</p>
                </div>
            @endif

        </div>
    </div>

    {{-- === SWITCH IMAGE WRAPPER === --}}
    <div class="col-md-3">

        <!-- TITLE + TOGGLE -->
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 class="fw-semibold text-primary-300 mb-0 ms-2">Image</h6>

            <div class="d-flex align-items-center">
                <span style="font-size: 12px; margin-right: 8px;">Minimap</span>

                <!-- TOGGLE SWITCH -->
                <label class="toggle-switch" style="cursor:pointer;">
                    <input type="checkbox"
                        wire:model.live="isBallHeatmap"
                        @checked($ballTab === 'heatmap')>
                    <span class="slider"></span>
                </label>

                <span style="font-size: 12px; margin-left: 8px;">Heatmap</span>
            </div>
        </div>

        <!-- IMAGE CONTENT -->
        <div class="image-wrapper position-relative">

            @if($ballTab==='minimap')
                @if ($balldropUrl)
                    <img src="{{ $balldropUrl }}"
                        class="w-100 rounded-4 shadow placeholder-wrapper"
                        style="height:520px; object-fit:cover;">
                @else
                    <div class="placeholder-wrapper rounded-4 bg-black-300
                        d-flex align-items-center justify-content-center w-100"
                        style="height:520px;">
                        <p class="fw-semibold text-white-400">Image in process...</p>
                    </div>
                @endif
            @endif

            @if($ballTab==='heatmap')
                @if ($imageHeatmapBalldroppingsUrl)
                    <img src="{{ $imageHeatmapBalldroppingsUrl }}"
                        class="w-100 rounded-4 shadow placeholder-wrapper"
                        style="height:520px; object-fit:cover;">
                @else
                    <div class="placeholder-wrapper rounded-4 bg-black-300
                        d-flex align-items-center justify-content-center w-100"
                        style="height:520px;">
                        <p class="fw-semibold text-white-400">Heatmap in process...</p>
                    </div>
                @endif
            @endif

        </div>

    </div>




    {{-- === AI TEXT === --}}
    <div class="col-md-6">
        <h6 class="fw-semibold text-primary-300 fw-bold mb-2 text-center">
            <i class="bi bi-stars me-2"></i>Insight
        </h6>

        <div class="p-3 bg-black-200 rounded-4 shadow-sm ai-box @if(strlen($text_balldrop ?? '') < 600) auto-fit @endif">
            @if ($text_balldrop)
                <p class="text-white-400 mb-0">{{ $text_balldrop }}</p>
            @else
                <p class="text-white-400 text-center mt-5">Insight in process...</p>
            @endif
        </div>
    </div>

</div>





</div>
