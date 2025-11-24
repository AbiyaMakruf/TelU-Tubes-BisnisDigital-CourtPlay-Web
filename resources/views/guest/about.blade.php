@extends('layouts.app')

@section('title', 'About CourtPlay')

@push('styles')
<style>
    .about-hero {
        position: relative;
        padding: 80px 0;
        overflow: hidden;
    }
    
    .about-hero::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -20%;
        width: 80%;
        height: 200%;
        background: radial-gradient(circle, rgba(var(--primary-300-rgb), 0.1) 0%, transparent 70%);
        z-index: -1;
    }

    .glass-card {
        background: rgba(255, 255, 255, 0.03);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 24px;
        padding: 2.5rem;
        height: 100%;
        transition: transform 0.3s ease;
    }

    .glass-card:hover {
        transform: translateY(-5px);
        border-color: rgba(var(--primary-300-rgb), 0.3);
    }

    .tech-badge {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 50px;
        padding: 8px 20px;
        color: var(--white-300);
        font-size: 0.9rem;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
    }

    .tech-badge:hover {
        background: rgba(var(--primary-300-rgb), 0.1);
        color: var(--primary-300);
        border-color: var(--primary-300);
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: 800;
        color: var(--primary-300);
        line-height: 1;
        margin-bottom: 0.5rem;
    }

    .stat-label {
        color: var(--white-400);
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
</style>
@endpush

@section('content')
<section class="about-hero">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-5 mb-lg-0">
                <h1 class="display-4 fw-bold text-white mb-4">
                    Revolutionizing <br>
                    <span class="text-primary-300">Racquet Sports</span> with AI
                </h1>
                <p class="lead text-white-300 mb-4">
                    CourtPlay bridges the gap between amateur enthusiasm and professional analytics. We use advanced computer vision to turn your match footage into actionable insights.
                </p>
            </div>
            <div class="col-lg-6 text-center py-5 py-lg-0">
                <div class="position-relative">
                    <div class="position-absolute top-50 start-50 translate-middle w-75 h-75 bg-primary-300 rounded-circle" style="filter: blur(60px); opacity: 0.2; z-index: -1;"></div>
                    <img src="{{ asset('assets/Logo Vertical.svg') }}" alt="CourtPlay Logo" class="img-fluid" style="max-height: 400px; filter: drop-shadow(0 0 20px rgba(0,0,0,0.5));">
                </div>
            </div>
        </div>
    </div>
</section>

<section id="our-story" class="py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="glass-card">
                    <i class="bi bi-rocket-takeoff fs-1 text-primary-300 mb-3"></i>
                    <h3 class="text-white fw-bold mb-3">Our Mission</h3>
                    <p class="text-white-400">
                        To democratize sports analytics. We believe that every player, regardless of their level, deserves access to the same data-driven insights used by top professionals to improve their game.
                    </p>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="glass-card">
                    <i class="bi bi-cpu fs-1 text-primary-300 mb-3"></i>
                    <h3 class="text-white fw-bold mb-3">The Technology</h3>
                    <p class="text-white-400">
                        Powered by cutting-edge AI models like Google Gemini and Ultralytics YOLO, CourtPlay processes video in real-time to track ball trajectory, player movement, and shot accuracy with high precision.
                    </p>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="glass-card">
                    <i class="bi bi-people fs-1 text-primary-300 mb-3"></i>
                    <h3 class="text-white fw-bold mb-3">Community First</h3>
                    <p class="text-white-400">
                        More than just a tool, CourtPlay is a community. Connect with other players, share your highlights, compare stats, and find your next opponent through our skill-based matchmaking.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="glass-card p-5">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <h2 class="fw-bold text-white mb-4">Built for the Future of Sport</h2>
                    <p class="text-white-300 mb-4">
                        CourtPlay started as a vision to solve a simple problem: "How can I know what I'm doing wrong on the court without hiring an expensive coach?"
                    </p>
                    <p class="text-white-300 mb-4">
                        Today, we are pushing the boundaries of what's possible with consumer hardware. No expensive sensors, no complex setups—just your smartphone camera and our cloud AI.
                    </p>
                    
                    <div class="d-flex flex-wrap gap-2 mt-4">
                        <span class="tech-badge"><i class="bi bi-google"></i> Google Cloud</span>
                        <span class="tech-badge"><i class="bi bi-cpu-fill"></i> Ultralytics YOLO</span>
                        <span class="tech-badge"><i class="bi bi-robot"></i> Gemini AI</span>
                        <span class="tech-badge"><i class="bi bi-database"></i> Supabase</span>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="row g-3 text-center">
                        <div class="col-6">
                            <div class="p-4 rounded-4" style="background: rgba(0,0,0,0.3);">
                                <div class="stat-number">98%</div>
                                <div class="stat-label">Detection Accuracy</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-4 rounded-4" style="background: rgba(0,0,0,0.3);">
                                <div class="stat-number">15m</div>
                                <div class="stat-label">Avg. Processing Time</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-4 rounded-4" style="background: rgba(0,0,0,0.3);">
                                <div class="stat-number">24/7</div>
                                <div class="stat-label">AI Coach Availability</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-4 rounded-4" style="background: rgba(0,0,0,0.3);">
                                <div class="stat-number">∞</div>
                                <div class="stat-label">Potential Growth</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5 text-center">
    <div class="container">
        <h2 class="text-white fw-bold mb-4">Ready to Level Up?</h2>
        <p class="text-white-300 mb-5" style="max-width: 600px; margin: 0 auto;">
            Join thousands of players who are already using CourtPlay to analyze their game and improve faster than ever before.
        </p>
        <a href="{{ route('signup.form') }}" class="btn btn-custom2 btn-lg px-5">Join CourtPlay Now</a>
    </div>
</section>
@endsection
