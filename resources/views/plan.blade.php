@extends('layouts.app')
@section('title', 'Pricing')

@section('content')
<section class="pricing py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-4 fw-bold text-white mb-3">Choose Your Plan</h2>
            <p class="text-white-300 fs-5">Unlock the full potential of your game with our flexible pricing options.</p>
        </div>

        <div class="row justify-content-center g-4">

            {{-- Free / Basic --}}
            <div class="col-lg-4 d-flex">
                <div class="pricing-card w-100 d-flex flex-column">
                    <div class="mb-4">
                        <h3 class="plan-name">{{ $plans['free']['name'] ?? 'Free' }}</h3>
                        <div class="plan-price">
                            {{ $plans['free']['price'] }}
                            <span>/month</span>
                        </div>
                    </div>

                    <ul class="feature-list list-unstyled mb-5 flex-grow-1">
                        @foreach($plans['free']['features'] as $feature)
                            <li><i class="bi bi-check-circle-fill"></i> {{ $feature }}</li>
                        @endforeach
                    </ul>

                    <div class="mt-auto">
                        @if($currentRole === 'free')
                            <button class="btn btn-plan active" disabled>
                                <i class="bi bi-check-lg me-2"></i>Current Plan
                            </button>
                        @else
                            <form method="POST" action="{{ route('payment.create') }}">
                                @csrf
                                <input type="hidden" name="plan" value="free">
                                <input type="hidden" name="price" value="{{ $plans['free']['price_idr'] }}">
                                <button type="submit" class="btn btn-plan">Choose Free</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Plus --}}
            <div class="col-lg-4 d-flex">
                <div class="pricing-card w-100 d-flex flex-column position-relative overflow-hidden">
                    <div class="ribbon-modern ribbon-blue">RECOMMENDED</div>
                    <div class="mb-4">
                        <h3 class="plan-name text-info">{{ $plans['plus']['name'] ?? 'Plus' }}</h3>
                        <div class="plan-price">
                            {{ $plans['plus']['price'] }}
                            <span>/month</span>
                        </div>
                    </div>

                    <ul class="feature-list list-unstyled mb-5 flex-grow-1">
                        @foreach($plans['plus']['features'] as $feature)
                            <li><i class="bi bi-check-circle-fill text-info"></i> {{ $feature }}</li>
                        @endforeach
                    </ul>

                    <div class="mt-auto">
                        @if($currentRole === 'plus')
                            <button class="btn btn-plan active" disabled>
                                <i class="bi bi-check-lg me-2"></i>Current Plan
                            </button>
                        @else
                            <form method="POST" action="{{ route('payment.create') }}">
                                @csrf
                                <input type="hidden" name="plan" value="plus">
                                <input type="hidden" name="price" value="{{ $plans['plus']['price_idr'] }}">
                                <button type="submit" class="btn btn-plan btn-plan-info">Upgrade to Plus</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Pro --}}
            <div class="col-lg-4 d-flex">
                <div class="pricing-card highlight w-100 d-flex flex-column position-relative overflow-hidden">
                    <div class="ribbon-modern">MOST POPULAR</div>
                    <div class="mb-4">
                        <h3 class="plan-name text-primary-300">{{ $plans['pro']['name'] ?? 'Pro' }}</h3>
                        <div class="plan-price">
                            {{ $plans['pro']['price'] }}
                            <span>/month</span>
                        </div>
                    </div>

                    <ul class="feature-list list-unstyled mb-5 flex-grow-1">
                        @foreach($plans['pro']['features'] as $feature)
                            <li><i class="bi bi-check-circle-fill"></i> {{ $feature }}</li>
                        @endforeach
                    </ul>

                    <div class="mt-auto">
                        @if($currentRole === 'pro')
                            <button class="btn btn-plan active" disabled>
                                <i class="bi bi-check-lg me-2"></i>Current Plan
                            </button>
                        @else
                            <form method="POST" action="{{ route('payment.create') }}">
                                @csrf
                                <input type="hidden" name="plan" value="pro">
                                <input type="hidden" name="price" value="{{ $plans['pro']['price_idr'] }}">
                                <button type="submit" class="btn btn-plan btn-plan-primary">Go Pro</button>
                            </form>
                        @endif
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
        margin-bottom: 2rem;
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

    .btn-plan.active {
        background: rgba(255,255,255,0.1);
        color: white;
        cursor: default;
        border: none;
    }

    .btn-plan.active:hover {
        transform: none;
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


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var modal = document.getElementById('changePlanModal');
    modal.addEventListener('show.bs.modal', function (event) {
        var btn = event.relatedTarget;
        var plan = btn.getAttribute('data-plan');
        var title = btn.getAttribute('data-title');
        document.getElementById('selectedPlanInput').value = plan;
        document.getElementById('selectedPlanLabel').textContent = title;
        document.getElementById('changePlanTitle').textContent = 'Change Plan to ' + title;
    });

    @if(session('toastr'))
        (function () {
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
        })();
    @endif
    @if(session('success')) toastr.success(@json(session('success'))); @endif
    @if(session('error'))   toastr.error(@json(session('error')));   @endif
    @if($errors->any())     toastr.error(@json($errors->first()));   @endif
});
</script>
@endpush
