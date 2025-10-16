@extends('layouts.app-auth')

@section('title', 'Analytics Detail')
@section('fullbleed', true)

@section('content')
<div class="container py-5 text-white">
    <h2 class="fw-bold text-primary-500 mb-2">{{ $project->project_name }}</h2>
    <p class="text-white-400 mb-4">Date: {{ \Carbon\Carbon::parse($project->upload_date)->format('d-m-Y') }}</p>

    <h5 class="fw-semibold text-primary-300 mb-3">Match Video</h5>
    <div class="video-wrapper mb-4 position-relative">
        @if ($videoUrl)
            <video controls class="w-100 rounded-4 shadow " style="max-height: 480px;">
                <source src="{{ $videoUrl }}" type="video/mp4">
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

    <div class="row g-4 align-items-start">
        <div class="col-md-6">
            @if ($heatmapUrl)
                <img src="{{ $heatmapUrl }}" alt="Heatmap" class="img-fluid rounded-4 shadow placeholder-wrapper">
            @else
                <div class="placeholder-wrapper  rounded-4 bg-black-300 d-flex align-items-center justify-content-center" style="height: 300px;">
                    <div class="text-center">
                        <i class="bi bi-bar-chart text-primary-300 mb-3 d-block" style="font-size: 4rem;"></i>
                        <p class="fw-semibold text-white-400">Heatmap in process...</p>
                    </div>
                </div>
            @endif
        </div>

        <div class="col-md-6">
            <div class="stat-bars">
                <p class="fw-semibold text-white-400 mb-1 d-flex justify-content-between">
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

    <div class="mt-5">
        <h5 class="fw-bold text-primary-500 mb-3">Statistic</h5>
        <p class="mb-1">Video duration: <span class="text-primary-300">{{ $videoDuration }}</span></p>
        <p>Video processing time: <span class="text-primary-300">{{ $processingTime }}</span></p>
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
