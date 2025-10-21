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
                        <li>✔ Free 1 video analytics</li>
                        <li>✔ Dashboard metrics</li>
                        <li>✔ AI mapping</li>
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
                        <li>✔ Up to 10 video analytics</li>
                        <li>✔ Dashboard metrics</li>
                        <li>✔ AI mapping</li>
                        <li>✔ Unlocked new feature</li>
                        <li>✔ Custom video analytics</li>
                        <li>✔ Unlimited storage</li>
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
                        <li>✔ Up to 100 video analytics</li>
                        <li>✔ Dashboard metrics</li>
                        <li>✔ AI mapping</li>
                        <li>✔ All features unlocked</li>
                        <li>✔ Custom video analytics</li>
                        <li>✔ Unlimited storage</li>
                        <li>✔ Heatmap systems</li>
                        <li>✔ Player report</li>
                        <li>✔ Communities sharing</li>
                    </ul>
                </div>
            </div>

        </div>
    </div>
</section>
@endsection
