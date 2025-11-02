@extends('layouts.app')

@section('title', 'Pricing')

@section('content')
<section class="pricing py-5">
    <div class="container">
        <div class="row justify-content-center g-4">

            {{-- Basic --}}
            <div class="col-md-4 d-flex">
                <div class="pricing-card basic w-100">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h3 class="mb-0">Free</h3>
                    </div>
                    <h4 class="price">Rp0 / month</h4>
                    <a href="{{ route('signup.form') }}" class="btn btn-outline-custom2 mb-3">Sign Up</a>
                    <hr>
                    <ul class="features list-unstyled mt-3">
                        <li>✔ 5 Analysis Videos</li>
                        <li>✔ 10 Minutes per Video</li>
                        <li>✔ Standard Match Analysis</li>
                        <li>✔ 2GB of Storages</li>
                    </ul>
                </div>
            </div>

            {{-- Plus --}}
            <div class="col-md-4 d-flex">
                <div class="pricing-card highlight w-100">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h3 class="mb-0">Plus</h3>
                    </div>
                    <h4 class="price">Rp129.000 / month</h4>
                    <a href="{{ route('signup.form') }}" class="btn btn-outline-custom mb-3">Buy Now</a>
                    <hr>
                    <ul class="features list-unstyled mt-3">
                        <li>✔ 15 Videos per Month</li>
                        <li>✔ 15 Minutes per Video</li>
                        <li>✔ Advanced Match Analysis</li>
                        <li>✔ 5GB of Storage</li>
                        <li>✔ Skill-Based Matchmaking</li>
                        <li>✔ Advanced Profile Customization</li>
                    </ul>
                </div>
            </div>

            {{-- Pro --}}
            <div class="col-md-4 d-flex">
                <div class="pricing-card pro w-100">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h3 class="mb-0">Pro</h3>
                    </div>
                    <h4 class="price">Rp299.000 / month</span></h4>
                    <a href="{{ route('signup.form') }}" class="btn btn-outline-custom mb-3">Buy Now</a>
                    <hr>
                    <ul class="features list-unstyled mt-3">
                        <li>✔ 40 Videos per Month</li>
                        <li>✔ 20 Minutes per Video</li>
                        <li>✔ Pro-Level Match Analysis</li>
                        <li>✔ 25GB of Storage</li>
                        <li>✔ Skill-Based Matchmaking</li>
                        <li>✔ Advanced Profile Customization</li>
                        <li>✔ AI-Generated Highlights</li>
                        <li>✔ AI-Coach Access</li>
                        <li>✔ Premium Profile Badge</li>
                    </ul>
                </div>
            </div>

        </div>
    </div>
</section>
@endsection
