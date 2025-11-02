@extends('layouts.app')

@section('title', 'Analytics Detail')
@section('fullbleed', true)

@section('content')
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

    <div class="row g-4 align-items-start mb-4">
        <div class="col-md-6">
            <h6 class="fw-semibold text-primary-300 mb-3 text-center">Heatmap Player</h6>
            @if ($heatmapUrl != "IN DEVELOPMENT" && $heatmapUrl != null)
                <img src="{{ $heatmapUrl }}" alt="Heatmap" class="img-fluid rounded-4 shadow placeholder-wrapper">
            @else
                <div class="placeholder-wrapper  rounded-4 bg-black-300 d-flex align-items-center justify-content-center" style="height: 300px;">
                    <div class="text-center">
                        <i class="bi bi-bar-chart text-primary-300 mb-3 d-block" style="font-size: 4rem;"></i>
                        <p class="fw-semibold text-white-400">Image is being process...</p>
                    </div>
                </div>
            @endif
        </div>

        <div class="col-md-6">
            <div class="p-3 bg-black-200 rounded-4 shadow-sm" style="margin-top: 2.1rem;  margin-bottom: 2rem;">
                @if ($text_heatmap != "IN DEVELOPMENT" && $text_heatmap != null)
                <h6 class="text-primary-300 fw-bold mb-2">
                    <i class="bi bi-stars me-2"></i>AI Generated Insight
                </h6>
                <p class="text-white-400 mb-0">
                    {{ $text_heatmap }}
                </p>
                @else
                    <div class="text-center ">
                        <p class="fw-semibold text-white-400 mt-3">Image is being process...</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="row g-4 align-items-start">
        <div class="col-md-6">
            <h6 class="fw-semibold text-primary-300 mb-3 text-center ">Ball Drop Heatmap</h6>
            @if ($balldropUrl != "IN DEVELOPMENT" && $balldropUrl != null)
                <img src="{{ $balldropUrl }}" alt="Ball Drop" class="img-fluid rounded-4 shadow placeholder-wrapper">
            @else
                <div class="placeholder-wrapper  rounded-4 bg-black-300 d-flex align-items-center justify-content-center" style="height: 300px;">
                    <div class="text-center">
                        <i class="bi bi-bar-chart text-primary-300 mb-3 d-block" style="font-size: 4rem;"></i>
                        <p class="fw-semibold text-white-400">Ball Drop in process...</p>
                    </div>
                </div>
            @endif
        </div>

        <div class="col-md-6 mt-6">
            <div class="p-3 bg-black-200 rounded-4 shadow-sm" style="margin-top: 2.1rem; margin-bottom: 2rem;">
                @if ($text_balldrop != "IN DEVELOPMENT" && $text_balldrop != null)
                <h6 class="text-primary-300 fw-bold mb-2">
                    <i class="bi bi-stars me-2"></i>AI Generated Insight
                </h6>
                <p class="text-white-400 mb-0">
                    {{ $text_balldrop }}
                </p>
                @else
                    <div class="text-center ">
                        <p class="fw-semibold text-white-400 mt-3">Insight in process...</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    @if(session('toastr'))
        var n = @json(session('toastr'));
        if (Array.isArray(n)) {
            n.forEach(function(item){
                if (item && item.type && item.message && typeof toastr[item.type] === 'function') {
                    toastr[item.type](item.message, item.title || '', item.options || {});
                }
            });
        } else if (n && n.type && n.message && typeof toastr[n.type] === 'function') {
            toastr[n.type](n.message, n.title || '', n.options || {});
        }
    @endif

    @if(session('success')) toastr.success(@json(session('success'))); @endif
    @if(session('error'))   toastr.error(@json(session('error')));   @endif
    @if($errors->any())     toastr.error(@json($errors->first()));   @endif
})();
</script>
@endpush
