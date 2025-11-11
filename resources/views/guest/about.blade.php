@extends('layouts.app')

@section('title', 'About Us')

@section('content')
<section class="about-us py-5">
    <div class="container">
        <div class="row align-items-center">


            <div class="col-md-6 text-center" >
                <img src="{{ asset('assets/Logo Vertical.svg') }}" alt="About Us Image" id="about-logo-section" class="img-fluid about-img">
            </div>

            {{-- Text Section --}}
            <div class="col-md-6">
                <h2 class="fw-bold mb-4">About Us</h2>
                <p class="text-primary-500">
                    CourtPlay is revolutionizing the tennis and padel experience by merging cutting-edge AI technology with a vibrant social community, empowering players with real-time performance analysis, from shot breakdowns to court positioning. Our platform simplifies match analysis and social interaction, enabling players to track their progress, connect with peers, and challenge others at their skill level, all in one single app. We are committed to fostering meaningful connections within the sports community while driving the future of racquet sports. At CourtPlay, our mission is to enhance every player's experience both on and off the court, combining advanced analytics with community-driven features.
                </p>
            </div>
        </div>
    </div>
</section>
@endsection
