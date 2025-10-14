@extends('layouts.app-auth')

@section('title', 'Analytics Detail')
@section('fullbleed', true)

@section('content')
<div class="container py-5 text-white">
    {{-- TITLE --}}
    <h2 class="fw-bold text-primary-500 mb-2">{{ $project->project_name }}</h2>
    <p class="text-white-400 mb-4">Date: {{ \Carbon\Carbon::parse($project->upload_date)->format('d-m-Y') }}</p>

    {{-- VIDEO SECTION --}}
    <h5 class="fw-semibold text-primary-300 mb-3">Match Video</h5>
    <div class="video-wrapper mb-4 position-relative">
        @if ($videoUrl)
            {{-- ✅ Tampilkan Video jika ada --}}
            <video controls class="w-100 rounded-4 shadow " style="max-height: 480px;">
                <source src="{{ $videoUrl }}" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        @else
            {{-- 🕓 Placeholder jika tidak ada video --}}
            <div class="placeholder-wrapper rounded-4 bg-black-300 d-flex align-items-center justify-content-center " style="height: 480px;">
                <div class="text-center">
                    <i class="bi bi-camera-video-off text-primary-300 mb-3 d-block" style="font-size: 5rem;"></i>
                    <p class="fw-semibold text-white-400">Video is being processed...</p>
                </div>
            </div>
        @endif
    </div>

    {{-- HEATMAP + BAR STATS --}}
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

        {{-- === BAR STATS === --}}
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

    {{-- STATISTICS --}}
    <div class="mt-5">
        <h5 class="fw-bold text-primary-500 mb-3">Statistic</h5>
        <p class="mb-1">Video duration: <span class="text-primary-300">{{ $videoDuration }}</span></p>
        <p>Video processing time: <span class="text-primary-300">{{ $processingTime }}</span></p>
    </div>
</div>

{{-- === STYLING === --}}
<style>
.video-wrapper video {
    border: 2px solid var(--primary-300);
}
.placeholder-wrapper {
    border: 2px solid var(--primary-300);
    transition: 0.3s ease;
}
.placeholder-wrapper:hover {
    box-shadow: 0 0 12px rgba(163, 206, 20, 0.3);
}
.bar-bg {
    background: #2b2b2b;
    border-radius: 10px;
    height: 18px;
    width: 100%;
    overflow: hidden;
}
.bar-fill {
    height: 100%;
    border-radius: 10px;
    background: linear-gradient(90deg, var(--primary-300), var(--primary-500));
    transition: width 0.8s ease;
}
@media (max-width: 768px) {
    .video-wrapper video, .placeholder-wrapper {
        max-height: 280px;
    }
}
</style>
@endsection
