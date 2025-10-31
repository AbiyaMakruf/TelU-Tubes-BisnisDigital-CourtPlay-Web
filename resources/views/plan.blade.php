@extends('layouts.app')
@section('title', 'Pricing')

@section('content')
<section class="pricing py-5">
    <div class="container">
        <div class="row justify-content-center g-4">

            {{-- Free / Basic --}}
            <div class="col-md-4 d-flex">
                <div class="pricing-card basic w-100">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h3 class="mb-0">{{ $plans['free']['name'] ?? 'Free' }}</h3>
                    </div>
                    <h4 class="price">{{ $plans['free']['price'] }}</h4>

                    @if($currentRole === 'free')
                        <button class="btn btn-outline-custom2 rounded-pill px-4 py-2 mb-3 w-100" disabled>Selected</button>
                        <div class="ribbon ribbon-top-right"><span>Current</span></div>
                    @else
                        <form method="POST" action="{{ route('payment.create') }}">
                            @csrf
                            <input type="hidden" name="plan" value="free">
                            <input type="hidden" name="price" value="{{ $plans['free']['price_idr'] }}">
                            <button type="submit" class="btn btn-outline-custom2 mb-3 w-100">Choose Plan</button>
                        </form>
                    @endif

                    <hr>
                    <ul class="features list-unstyled mt-3">
                        @foreach($plans['free']['features'] as $feature)
                            <li>✔ {{ $feature }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>

            {{-- Plus --}}
            <div class="col-md-4 d-flex">
                <div class="pricing-card highlight w-100">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h3 class="mb-0">{{ $plans['plus']['name'] ?? 'Plus' }}</h3>
                    </div>
                    <h4 class="price">{{ $plans['plus']['price'] }}</h4>

                    @if($currentRole === 'plus')
                        <button class="btn btn-outline-dark rounded-pill px-4 py-2 mb-3 w-100" disabled>Selected</button>
                        <div class="ribbon ribbon-top-right"><span>Current</span></div>
                    @else
                        <form method="POST" action="{{ route('payment.create') }}">
                            @csrf
                            <input type="hidden" name="plan" value="plus">
                            <input type="hidden" name="price" value="{{ $plans['plus']['price_idr'] }}">
                            <button type="submit" class="btn btn-outline-custom mb-3 w-100">Choose Plan</button>
                        </form>
                    @endif

                    <hr>
                    <ul class="features list-unstyled mt-3">
                        @foreach($plans['plus']['features'] as $feature)
                            <li>✔ {{ $feature }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>

            {{-- Pro --}}
            <div class="col-md-4 d-flex">
                <div class="pricing-card pro w-100">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h3 class="mb-0">{{ $plans['pro']['name'] ?? 'Pro' }}</h3>
                    </div>
                    <h4 class="price">{{ $plans['pro']['price'] }}</h4>

                    @if($currentRole === 'pro')
                        <button class="btn btn-outline-dark rounded-pill px-4 py-2 mb-3 w-100" disabled>Selected</button>
                        <div class="ribbon ribbon-top-right"><span>Current</span></div>
                    @else
                        <form method="POST" action="{{ route('payment.create') }}">
                            @csrf
                            <input type="hidden" name="plan" value="pro">
                            <input type="hidden" name="price" value="{{ $plans['pro']['price_idr'] }}">
                            <button type="submit" class="btn btn-outline-custom mb-3 w-100">Choose Plan</button>
                        </form>
                    @endif

                    <hr>
                    <ul class="features list-unstyled mt-3">
                        @foreach($plans['pro']['features'] as $feature)
                            <li>✔ {{ $feature }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>

        </div>
    </div>
</section>
@endsection

@push('styles')
<style>

/* --- Ribbon Style --- */
.ribbon {
    width: 100px;
    height: 100px;
    overflow: hidden;
    position: absolute;
    top: -5px;
    right: -5px;
}
.ribbon span {
    position: absolute;
    display: block;
    width: 140px;
    padding: 5px 0;
    background: #ffc107;
    color: #111;
    text-align: center;
    font-weight: 600;
    transform: rotate(45deg);
    top: 25px;
    right: -30px;
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
