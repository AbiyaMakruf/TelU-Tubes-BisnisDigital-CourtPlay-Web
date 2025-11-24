@extends('layouts.app')

@section('title', 'Pricing')

@section('content')
<section class="pricing py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-4 fw-bold text-white mb-3">Choose Your Plan</h2>
            <p class="text-white-300 fs-5">Unlock your potential with the right tools for your game.</p>
        </div>

        <div class="row justify-content-center g-4">

            {{-- Free / Basic --}}
            <div class="col-lg-4 d-flex">
                <div class="pricing-card w-100 d-flex flex-column">
                    <div class="mb-4">
                        <h3 class="plan-name">Free</h3>
                        <div class="plan-price">
                            Rp0
                            <span>/mo</span>
                        </div>
                        <p class="text-white-50 small mb-0">Perfect for getting started with basic analysis.</p>
                    </div>

                    <ul class="feature-list list-unstyled mb-5 flex-grow-1">
                        <li><i class="bi bi-check-circle-fill"></i> 5 Analysis Videos</li>
                        <li><i class="bi bi-check-circle-fill"></i> 10 Minutes per Video</li>
                        <li><i class="bi bi-check-circle-fill"></i> Standard Match Analysis</li>
                        <li><i class="bi bi-check-circle-fill"></i> 2GB Storage</li>
                    </ul>

                    <div class="mt-auto">
                        <a href="{{ route('signup.form') }}" class="btn btn-plan">Get Started</a>
                    </div>
                </div>
            </div>

            {{-- Plus --}}
            <div class="col-lg-4 d-flex">
                <div class="pricing-card w-100 d-flex flex-column position-relative overflow-hidden">
                    <div class="ribbon-modern ribbon-blue">RECOMMENDED</div>
                    <div class="mb-4">
                        <h3 class="plan-name text-info">Plus</h3>
                        <div class="plan-price">
                            Rp129k
                            <span>/mo</span>
                        </div>
                        <p class="text-white-50 small mb-0">For dedicated players who want deeper insights.</p>
                    </div>

                    <ul class="feature-list list-unstyled mb-5 flex-grow-1">
                        <li><i class="bi bi-check-circle-fill text-info"></i> 15 Videos per Month</li>
                        <li><i class="bi bi-check-circle-fill text-info"></i> 15 Minutes per Video</li>
                        <li><i class="bi bi-check-circle-fill text-info"></i> Advanced Match Analysis</li>
                        <li><i class="bi bi-check-circle-fill text-info"></i> 5GB Storage</li>
                        <li><i class="bi bi-check-circle-fill text-info"></i> Skill-Based Matchmaking</li>
                    </ul>

                    <div class="mt-auto">
                        <a href="{{ route('signup.form') }}" class="btn btn-plan btn-plan-info">Choose Plus</a>
                    </div>
                </div>
            </div>

            {{-- Pro --}}
            <div class="col-lg-4 d-flex">
                <div class="pricing-card highlight w-100 d-flex flex-column position-relative overflow-hidden">
                    <div class="ribbon-modern">MOST POPULAR</div>
                    <div class="mb-4">
                        <h3 class="plan-name text-primary-300">Pro</h3>
                        <div class="plan-price">
                            Rp299k
                            <span>/mo</span>
                        </div>
                        <p class="text-white-50 small mb-0">The ultimate toolkit for professionals and coaches.</p>
                    </div>

                    <ul class="feature-list list-unstyled mb-5 flex-grow-1">
                        <li><i class="bi bi-check-circle-fill"></i> 40 Videos per Month</li>
                        <li><i class="bi bi-check-circle-fill"></i> 20 Minutes per Video</li>
                        <li><i class="bi bi-check-circle-fill"></i> Pro-Level Match Analysis</li>
                        <li><i class="bi bi-check-circle-fill"></i> 25GB Storage</li>
                        <li><i class="bi bi-check-circle-fill"></i> AI-Generated Highlights</li>
                        <li><i class="bi bi-check-circle-fill"></i> AI-Coach Access</li>
                    </ul>

                    <div class="mt-auto">
                        <a href="{{ route('signup.form') }}" class="btn btn-plan btn-plan-primary">Go Pro</a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    /* Modern Pricing Styles */
    .pricing-card {
        background: rgba(255, 255, 255, 0.03);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 24px;
        padding: 2.5rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    .pricing-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.5);
        border-color: rgba(var(--primary-300-rgb), 0.3);
    }

    .pricing-card.highlight {
        background: linear-gradient(145deg, rgba(var(--primary-300-rgb), 0.15) 0%, rgba(255, 255, 255, 0.05) 100%);
        border: 2px solid var(--primary-300);
        box-shadow: 0 0 40px rgba(var(--primary-300-rgb), 0.2);
        transform: scale(1.05);
        z-index: 2;
    }

    .pricing-card.highlight:hover {
        box-shadow: 0 0 60px rgba(var(--primary-300-rgb), 0.3);
        transform: scale(1.05) translateY(-10px);
    }

    @media (max-width: 991.98px) {
        .pricing-card.highlight {
            transform: scale(1);
            margin-top: 1rem;
            margin-bottom: 1rem;
        }
        .pricing-card.highlight:hover {
            transform: translateY(-5px);
        }
    }

    .plan-name {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--white-500);
        margin-bottom: 0.5rem;
    }

    .plan-price {
        font-size: 3rem;
        font-weight: 800;
        color: white;
        line-height: 1;
        margin-bottom: 1rem;
    }

    .plan-price span {
        font-size: 1rem;
        font-weight: 400;
        color: var(--white-300);
    }

    .feature-list li {
        margin-bottom: 1rem;
        color: var(--white-400);
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .feature-list li i {
        color: var(--primary-300);
        font-size: 1.2rem;
    }

    .btn-plan {
        width: 100%;
        padding: 1rem;
        border-radius: 50px;
        font-weight: 600;
        transition: all 0.3s ease;
        border: 1px solid rgba(255,255,255,0.2);
        background: transparent;
        color: white;
        text-decoration: none;
        display: block;
        text-align: center;
    }

    .btn-plan:hover {
        background: rgba(255,255,255,0.1);
        color: white;
        transform: scale(1.02);
    }

    .btn-plan-primary {
        background: var(--primary-300);
        color: black;
        border: none;
        box-shadow: 0 4px 15px rgba(var(--primary-300-rgb), 0.4);
    }

    .btn-plan-primary:hover {
        background: #b4e61a;
        color: black;
        box-shadow: 0 6px 20px rgba(var(--primary-300-rgb), 0.6);
    }

    .btn-plan-info {
        border-color: #0dcaf0;
        color: #0dcaf0;
    }

    .btn-plan-info:hover {
        background: rgba(13, 202, 240, 0.1);
        color: #0dcaf0;
        box-shadow: 0 0 15px rgba(13, 202, 240, 0.2);
    }

    .ribbon-modern {
        position: absolute;
        top: 35px;
        right: -55px;
        width: 200px;
        background: var(--primary-300);
        color: #000;
        padding: 6px 0;
        text-align: center;
        transform: rotate(45deg);
        font-size: 0.75rem;
        font-weight: 800;
        letter-spacing: 0.5px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        z-index: 10;
        text-transform: uppercase;
    }

    .ribbon-blue {
        background: #0dcaf0;
        color: #000;
    }
</style>
@endpush
