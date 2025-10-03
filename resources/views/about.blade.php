@extends('layouts.app')

@section('title', 'About Us')

@section('content')
<section class="about-us py-5">
    <div class="container">
        <div class="row align-items-center">

            {{-- Text Section --}}
            <div class="col-md-6">
                <h2 class="fw-bold mb-4">About Us</h2>
                <p class="text-primary-500">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                    Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                </p>
                <p class="text-primary-500">
                    Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.
                    Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                </p>
                {{-- <a href="#" class="btn btn-custom2 mt-3">Learn More</a> --}}
            </div>

            {{-- Image Section --}}
            <div class="col-md-6 text-center">
                <img src="{{ asset('assets/img-1.svg') }}" alt="About Us Image" class="img-fluid about-img">
            </div>
        </div>
    </div>
</section>
@endsection
