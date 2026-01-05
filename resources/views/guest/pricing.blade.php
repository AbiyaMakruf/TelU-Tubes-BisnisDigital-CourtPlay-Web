@extends('layouts.app')

@section('title', 'Pricing')

@section('content')
<section class="pricing py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-4 fw-bold text-white mb-3">Choose Your Plan</h2>
            <p class="text-white-300 fs-5">Unlock your potential with the right tools for your game.</p>
            <div class="mt-3">
                <span class="badge bg-success fs-6 px-3 py-2 me-2">
                    <i class="bi bi-shield-check"></i> Secure Payment
                </span>
                <span class="badge bg-info fs-6 px-3 py-2">
                    <i class="bi bi-arrow-repeat"></i> Flexible Cancellation
                </span>
            </div>
        </div>

        <div class="row justify-content-center g-4">

            {{-- Free / Basic --}}
            <div class="col-lg-3 col-md-6 d-flex">
                <div class="pricing-card pricing-card-free w-100 d-flex flex-column">
                    <div class="mb-3">
                        <h3 class="plan-name">Free</h3>
                        <div class="plan-price" style="font-size: 2.5rem;">
                            Rp0
                            <span style="font-size: 0.9rem;">/mo</span>
                        </div>
                        <p class="text-white-50 small mb-2">Perfect for getting started with basic analysis.</p>
                    </div>

                    <ul class="feature-list list-unstyled mb-4 flex-grow-1">
                        <li><i class="bi bi-check-circle-fill"></i> 2 Analysis Videos</li>
                        <li><i class="bi bi-check-circle-fill"></i> 5 Minutes per Video</li>
                        <li><i class="bi bi-check-circle-fill"></i> Standard Match Analysis</li>
                        <li><i class="bi bi-check-circle-fill"></i> 2GB Storage</li>
                        <li><i class="bi bi-check-circle-fill"></i> AI-Generated Highlights</li>
                    </ul>

                    <div class="mt-auto">
                        <a href="{{ route('signup.form') }}" class="btn btn-plan">Get Started</a>
                    </div>
                </div>
            </div>

            {{-- Starter - New Package --}}
            <div class="col-lg-3 col-md-6 d-flex">
                <div class="pricing-card pricing-card-warning w-100 d-flex flex-column">
                    <div class="badge-new-package">NEW</div>
                    <div class="mb-3">
                        <h3 class="plan-name text-warning">Starter</h3>
                        <div class="plan-price" style="font-size: 2.5rem;">
                            Rp19k
                            <span style="font-size: 0.9rem;">/one-time</span>
                        </div>
                        <p class="text-white-50 small mb-2">Try our platform with limited videos.</p>
                        <div class="cancel-badge">
                            <i class="bi bi-x-circle"></i> Cancel Anytime
                        </div>
                    </div>

                    <ul class="feature-list list-unstyled mb-4 flex-grow-1">
                        <li><i class="bi bi-check-circle-fill text-warning"></i> 5 Analysis Videos</li>
                        <li><i class="bi bi-check-circle-fill text-warning"></i> 10 Minutes per Video</li>
                        <li><i class="bi bi-check-circle-fill text-warning"></i> Standard Match Analysis</li>
                        <li><i class="bi bi-check-circle-fill text-warning"></i> 2GB Storage</li>
                        <li><i class="bi bi-check-circle-fill text-warning"></i> Basic Analytics Dashboard</li>
                        <li><i class="bi bi-check-circle-fill text-warning"></i> AI-Generated Highlights</li>
                    </ul>

                    <div class="mt-auto">
                        <a href="{{ route('signup.form') }}" class="btn btn-plan btn-plan-warning">Buy Once</a>
                    </div>
                </div>
            </div>

            {{-- Plus --}}
            <div class="col-lg-3 col-md-6 d-flex">
                <div class="pricing-card pricing-card-info w-100 d-flex flex-column position-relative overflow-hidden">
                    <div class="ribbon-modern ribbon-blue">RECOMMENDED</div>
                    
                    <div class="mb-3">
                        {{-- Free Trial Badge - moved below ribbon --}}
                        <div class="special-offer-badge-inline mb-2">
                            <i class="bi bi-gift"></i> 1st Month FREE
                        </div>
                        
                        <h3 class="plan-name text-info">Plus</h3>
                        <div class="plan-price" style="font-size: 2.5rem;">
                            <span class="small text-decoration-line-through text-white-50 d-block mb-1">Rp129k</span>
                            <span class="free-trial-text" style="font-size: 2.5rem;">Rp0</span>
                            <span class="small d-block text-white-50 mt-1">Then Rp129k/mo</span>
                        </div>
                        <p class="text-white-50 small mb-2">For dedicated players who want deeper insights.</p>
                        <div class="cancel-badge">
                            <i class="bi bi-x-circle"></i> Cancel Anytime
                        </div>
                    </div>

                    <ul class="feature-list list-unstyled mb-4 flex-grow-1">
                        <li><i class="bi bi-check-circle-fill text-info"></i> 15 Videos per Month</li>
                        <li><i class="bi bi-check-circle-fill text-info"></i> 15 Minutes per Video</li>
                        <li><i class="bi bi-check-circle-fill text-info"></i> Advanced Match Analysis</li>
                        <li><i class="bi bi-check-circle-fill text-info"></i> 5GB Storage</li>
                        <li><i class="bi bi-check-circle-fill text-info"></i> Skill-Based Matchmaking</li>
                        <li><i class="bi bi-check-circle-fill text-info"></i> AI-Generated Highlights</li>
                    </ul>

                    <div class="mt-auto">
                        <a href="{{ route('signup.form') }}" class="btn btn-plan btn-plan-info">Start Free Trial</a>
                    </div>
                </div>
            </div>

            {{-- Pro --}}
            <div class="col-lg-3 col-md-6 d-flex">
                <div class="pricing-card highlight w-100 d-flex flex-column position-relative overflow-hidden">
                    <div class="ribbon-modern">MOST POPULAR</div>
                    
                    <div class="mb-3">
                        {{-- Discount Badge - moved below ribbon --}}
                        <div class="discount-badge-inline mb-2">
                            <i class="bi bi-percent"></i> 30% OFF
                        </div>
                        
                        <h3 class="plan-name text-primary-300">Pro</h3>
                        <div class="plan-price" style="font-size: 2.5rem;">
                            <span class="small text-decoration-line-through text-white-50 d-block mb-1">Rp299k</span>
                            <span style="font-size: 2.5rem;">Rp209k</span>
                            <span style="font-size: 0.9rem;">/first mo</span>
                            <span class="small d-block text-white-50 mt-1">Then Rp299k/mo</span>
                        </div>
                        <p class="text-white-50 small mb-2">The ultimate toolkit for professionals and coaches.</p>
                        <div class="cancel-badge">
                            <i class="bi bi-x-circle"></i> Cancel Anytime
                        </div>
                    </div>

                    <ul class="feature-list list-unstyled mb-4 flex-grow-1">
                        <li><i class="bi bi-check-circle-fill"></i> 40 Videos per Month</li>
                        <li><i class="bi bi-check-circle-fill"></i> 20 Minutes per Video</li>
                        <li><i class="bi bi-check-circle-fill"></i> Pro-Level Match Analysis</li>
                        <li><i class="bi bi-check-circle-fill"></i> 25GB Storage</li>
                        <li><i class="bi bi-check-circle-fill"></i> AI-Generated Highlights</li>
                        <li><i class="bi bi-check-circle-fill"></i> AI-Coach Access</li>
                    </ul>

                    <div class="mt-auto">
                        <a href="{{ route('signup.form') }}" class="btn btn-plan btn-plan-primary">Go Pro Now</a>
                    </div>
                </div>
            </div>

        </div>

        {{-- Comparison Table --}}
        <div class="row mt-5 pt-4">
            <div class="col-12">
                <h3 class="text-center text-white mb-4">Compare Plans</h3>
                <div class="table-responsive">
                    <table class="table table-dark table-hover comparison-table">
                        <thead>
                            <tr>
                                <th class="text-white-400">Features</th>
                                <th class="text-center">Free</th>
                                <th class="text-center text-warning">Starter</th>
                                <th class="text-center text-info">Plus</th>
                                <th class="text-center text-primary-300">Pro</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-white-400"><i class="bi bi-film me-2"></i>Videos per Month</td>
                                <td class="text-center">2</td>
                                <td class="text-center">5 (One-time)</td>
                                <td class="text-center">15</td>
                                <td class="text-center"><strong>40</strong></td>
                            </tr>
                            <tr>
                                <td class="text-white-400"><i class="bi bi-clock me-2"></i>Minutes per Video</td>
                                <td class="text-center">5</td>
                                <td class="text-center">10</td>
                                <td class="text-center">15</td>
                                <td class="text-center"><strong>20</strong></td>
                            </tr>
                            <tr>
                                <td class="text-white-400"><i class="bi bi-hdd me-2"></i>Storage</td>
                                <td class="text-center">2GB</td>
                                <td class="text-center">2GB</td>
                                <td class="text-center">5GB</td>
                                <td class="text-center"><strong>25GB</strong></td>
                            </tr>
                            <tr>
                                <td class="text-white-400"><i class="bi bi-bar-chart me-2"></i>Match Analysis</td>
                                <td class="text-center">Standard</td>
                                <td class="text-center">Standard</td>
                                <td class="text-center">Advanced</td>
                                <td class="text-center"><strong>Pro-Level</strong></td>
                            </tr>
                            <tr>
                                <td class="text-white-400"><i class="bi bi-trophy me-2"></i>Skill-Based Matchmaking</td>
                                <td class="text-center"><i class="bi bi-x-circle text-danger"></i></td>
                                <td class="text-center"><i class="bi bi-x-circle text-danger"></i></td>
                                <td class="text-center"><i class="bi bi-check-circle text-info"></i></td>
                                <td class="text-center"><i class="bi bi-check-circle text-success"></i></td>
                            </tr>
                            <tr>
                                <td class="text-white-400"><i class="bi bi-stars me-2"></i>AI-Generated Highlights</td>
                                <td class="text-center"><i class="bi bi-check-circle text-success"></i></td>
                                <td class="text-center"><i class="bi bi-check-circle text-success"></i></td>
                                <td class="text-center"><i class="bi bi-check-circle text-success"></i></td>
                                <td class="text-center"><i class="bi bi-check-circle text-success"></i></td>
                            </tr>
                            <tr>
                                <td class="text-white-400"><i class="bi bi-robot me-2"></i>AI-Coach Access</td>
                                <td class="text-center"><i class="bi bi-x-circle text-danger"></i></td>
                                <td class="text-center"><i class="bi bi-x-circle text-danger"></i></td>
                                <td class="text-center"><i class="bi bi-x-circle text-danger"></i></td>
                                <td class="text-center"><i class="bi bi-check-circle text-success"></i></td>
                            </tr>
                            <tr>
                                <td class="text-white-400"><i class="bi bi-tag me-2"></i>Price</td>
                                <td class="text-center"><strong>Rp0</strong></td>
                                <td class="text-center"><strong class="text-warning">Rp19k</strong><br><small class="text-white-50">One-time</small></td>
                                <td class="text-center"><strong class="text-info">Rp0</strong><br><small class="text-white-50">1st month free</small></td>
                                <td class="text-center"><strong class="text-primary-300">Rp209k</strong><br><small class="text-white-50">30% off 1st month</small></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- FAQ Section --}}
        <div class="row mt-5 pt-4">
            <div class="col-lg-8 mx-auto">
                <h3 class="text-center text-white mb-4">Frequently Asked Questions</h3>
                <div class="accordion accordion-flush" id="pricingFAQ">
                    <div class="accordion-item bg-transparent border-0 mb-3">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed bg-dark-2 text-white rounded" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                <i class="bi bi-question-circle me-2"></i> Can I upgrade or downgrade my plan?
                            </button>
                        </h2>
                        <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#pricingFAQ">
                            <div class="accordion-body bg-dark-2 text-white-400 rounded-bottom">
                                Yes! You can upgrade or downgrade your plan anytime. Changes will be reflected in your next billing cycle.
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item bg-transparent border-0 mb-3">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed bg-dark-2 text-white rounded" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                <i class="bi bi-question-circle me-2"></i> How does the free trial work for Plus plan?
                            </button>
                        </h2>
                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#pricingFAQ">
                            <div class="accordion-body bg-dark-2 text-white-400 rounded-bottom">
                                Your first month is completely free! After 30 days, you'll be charged Rp129k/month. You can cancel anytime during the trial period with no charges.
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item bg-transparent border-0 mb-3">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed bg-dark-2 text-white rounded" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                <i class="bi bi-question-circle me-2"></i> What payment methods do you accept?
                            </button>
                        </h2>
                        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#pricingFAQ">
                            <div class="accordion-body bg-dark-2 text-white-400 rounded-bottom">
                                We accept all major payment methods including credit cards, debit cards, bank transfers, and e-wallets through our secure payment gateway.
                            </div>
                        </div>
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
        padding: 2rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        min-height: 520px;
    }

    .pricing-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 35px -10px rgba(0, 0, 0, 0.5);
        border-color: rgba(var(--primary-300-rgb), 0.3);
    }

    /* Glowing Effects for Each Card */
    .pricing-card-free {
        border: 2px solid rgba(108, 117, 125, 0.3);
        box-shadow: 0 0 20px rgba(108, 117, 125, 0.15);
    }

    .pricing-card-free:hover {
        box-shadow: 0 0 35px rgba(108, 117, 125, 0.3);
        border-color: rgba(108, 117, 125, 0.5);
    }

    .pricing-card-warning {
        border: 2px solid rgba(255, 193, 7, 0.3);
        box-shadow: 0 0 20px rgba(255, 193, 7, 0.15);
    }

    .pricing-card-warning:hover {
        box-shadow: 0 0 35px rgba(255, 193, 7, 0.3);
        border-color: rgba(255, 193, 7, 0.5);
    }

    .pricing-card-info {
        border: 2px solid rgba(13, 202, 240, 0.3);
        box-shadow: 0 0 20px rgba(13, 202, 240, 0.15);
    }

    .pricing-card-info:hover {
        box-shadow: 0 0 35px rgba(13, 202, 240, 0.3);
        border-color: rgba(13, 202, 240, 0.5);
    }

    .pricing-card.highlight {
        background: linear-gradient(145deg, rgba(var(--primary-300-rgb), 0.15) 0%, rgba(255, 255, 255, 0.05) 100%);
        border: 2px solid var(--primary-300);
        box-shadow: 0 0 30px rgba(var(--primary-300-rgb), 0.25);
        transform: scale(1.02);
        z-index: 2;
    }

    .pricing-card.highlight:hover {
        box-shadow: 0 0 45px rgba(var(--primary-300-rgb), 0.4);
        transform: scale(1.02) translateY(-8px);
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
        
        .pricing-card {
            min-height: auto;
        }
    }

    .plan-name {
        font-size: 1.4rem;
        font-weight: 700;
        color: var(--white-500);
        margin-bottom: 0.5rem;
    }

    .plan-price {
        font-size: 2.5rem;
        font-weight: 800;
        color: white;
        line-height: 1.2;
        margin-bottom: 0.8rem;
    }

    .plan-price span {
        font-size: 0.9rem;
        font-weight: 400;
        color: var(--white-300);
    }

    .free-trial-text {
        color: #0dcaf0;
    }

    /* Badge Styles */
    .badge-new-package {
        position: absolute;
        top: 15px;
        right: 15px;
        background: linear-gradient(135deg, #ffc107, #ff9800);
        color: #000;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 800;
        letter-spacing: 0.5px;
        box-shadow: 0 2px 10px rgba(255, 193, 7, 0.4);
        z-index: 10;
    }

    /* Inline badges - smaller and positioned below ribbon */
    .special-offer-badge-inline,
    .discount-badge-inline {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 15px;
        font-size: 0.7rem;
        font-weight: 700;
        text-align: center;
        animation: pulse 2s infinite;
    }

    .special-offer-badge-inline {
        background: linear-gradient(135deg, #0dcaf0, #0b96bf);
        color: #000;
        box-shadow: 0 2px 8px rgba(13, 202, 240, 0.3);
    }

    .discount-badge-inline {
        background: linear-gradient(135deg, var(--primary-300), #b4e61a);
        color: #000;
        box-shadow: 0 2px 8px rgba(var(--primary-300-rgb), 0.3);
    }

    .cancel-badge {
        background: rgba(255, 255, 255, 0.1);
        color: #fff;
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 0.7rem;
        display: inline-block;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    @keyframes pulse {
        0%, 100% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.05);
        }
    }

    .feature-list li {
        margin-bottom: 0.75rem;
        color: var(--white-400);
        display: flex;
        align-items: center;
        gap: 0.6rem;
        font-size: 0.9rem;
    }

    .feature-list li i {
        color: var(--primary-300);
        font-size: 1.1rem;
        flex-shrink: 0;
    }

    .btn-plan {
        width: 100%;
        padding: 0.85rem;
        border-radius: 50px;
        font-weight: 600;
        transition: all 0.3s ease;
        border: 1px solid rgba(255,255,255,0.2);
        background: transparent;
        color: white;
        text-decoration: none;
        display: block;
        text-align: center;
        font-size: 0.95rem;
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

    .btn-plan-warning {
        border-color: #ffc107;
        color: #ffc107;
    }

    .btn-plan-warning:hover {
        background: rgba(255, 193, 7, 0.1);
        color: #ffc107;
        box-shadow: 0 0 15px rgba(255, 193, 7, 0.2);
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

    /* Comparison Table Styles */
    .comparison-table {
        background: rgba(255, 255, 255, 0.03);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 16px;
        overflow: hidden;
    }

    .comparison-table thead {
        background: rgba(var(--primary-300-rgb), 0.1);
    }

    .comparison-table th {
        padding: 1.2rem 1rem;
        font-weight: 700;
        border-bottom: 2px solid rgba(255, 255, 255, 0.1);
        color: white;
        font-size: 0.95rem;
    }

    .comparison-table td {
        padding: 1rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        vertical-align: middle;
        color: var(--white-300);
    }

    .comparison-table tbody tr:hover {
        background: rgba(255, 255, 255, 0.03);
    }

    .comparison-table tbody tr:last-child td {
        border-bottom: none;
    }

    .table-responsive {
        border-radius: 16px;
        overflow-x: auto;
    }

    /* FAQ Styles */
    .accordion-button {
        font-weight: 600;
        padding: 1rem;
        font-size: 0.95rem;
    }

    .accordion-button:not(.collapsed) {
        background-color: rgba(var(--primary-300-rgb), 0.2);
        color: var(--primary-300);
        border-bottom: 2px solid var(--primary-300);
    }

    .accordion-button:focus {
        box-shadow: none;
        border-color: var(--primary-300);
    }

    .accordion-body {
        padding: 1rem;
        line-height: 1.6;
        font-size: 0.9rem;
    }

    .bg-dark-2 {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    /* Responsive adjustments */
    @media (max-width: 991.98px) {
        .plan-price {
            font-size: 2rem;
        }
        
        .pricing-card {
            padding: 1.75rem;
        }
        
        .special-offer-badge-inline,
        .discount-badge-inline {
            font-size: 0.65rem;
            padding: 3px 8px;
        }

        .comparison-table th,
        .comparison-table td {
            padding: 0.75rem 0.5rem;
            font-size: 0.85rem;
        }
    }

    @media (max-width: 767.98px) {
        .plan-price {
            font-size: 1.8rem;
        }
        
        .pricing-card {
            padding: 1.5rem;
        }

        .plan-name {
            font-size: 1.2rem;
        }

        .feature-list li {
            font-size: 0.85rem;
            margin-bottom: 0.6rem;
        }

        .btn-plan {
            padding: 0.75rem;
            font-size: 0.9rem;
        }

        .comparison-table {
            font-size: 0.8rem;
        }

        .comparison-table th,
        .comparison-table td {
            padding: 0.6rem 0.4rem;
            font-size: 0.75rem;
        }
    }
</style>
@endpush
