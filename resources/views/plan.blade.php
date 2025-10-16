@extends('layouts.app-auth')

@section('title', 'Your Plan')
@section('fullbleed', true)

@section('content')
@php
    $plans = $plans ?? [];
    $currentRole = strtolower($currentRole ?? (optional(Auth::user())->role ?? 'free'));
@endphp

<div class="container py-5 text-white">
    <h2 class="fw-bold text-primary-500 text-center mb-4">Choose Your Plan</h2>
    <p class="text-center text-white-400 mb-5">Switch plan during development. Your role will update immediately.</p>

    <div class="row g-4 justify-content-center">
        @foreach($plans as $key => $plan)
            @php $isCurrent = ($currentRole === $key); @endphp
            <div class="col-md-4">
                <div class="pricing-card position-relative h-100 rounded-4 shadow-sm p-4" style="background: {{ $plan['tone'] }}; color: #111;">
                    @if($isCurrent)
                        <div class="ribbon ribbon-top-right"><span>Current</span></div>
                    @endif

                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h4 class="fw-bold mb-0">{{ $plan['name'] }}</h4>
                        <small class="text-muted">{{ $plan['users'] }}</small>
                    </div>

                    <div class="mb-3">
                        <div class="fs-3 fw-bold">{{ $plan['price'] }}</div>
                        <div class="small text-muted">Max {{ $plan['limit'] }} videos • {{ $plan['max_mb'] }} MB/file</div>
                    </div>

                    <button
                        class="btn btn-outline-dark rounded-pill px-4 py-2 mb-3"
                        @if($isCurrent) disabled @endif
                        data-bs-toggle="modal"
                        data-bs-target="#changePlanModal"
                        data-plan="{{ $key }}"
                        data-title="{{ $plan['name'] }}"
                    >
                        {{ $isCurrent ? 'Selected' : 'Choose Plan' }}
                    </button>

                    <hr class="my-3" style="border-color: rgba(0,0,0,.1)">

                    <ul class="list-unstyled mb-0">
                        @foreach($plan['features'] as $f)
                            <li class="mb-2">✔ {{ $f }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endforeach
    </div>
</div>

<div class="modal fade" id="changePlanModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <form class="modal-content bg-black-200 text-white" method="POST" action="{{ route('plan.change') }}">
      @csrf
      <div class="modal-header border-0">
        <h5 class="modal-title fw-bold text-primary-500" id="changePlanTitle">Change Plan</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="plan" id="selectedPlanInput">
        <p class="mb-1">You are switching plan to: <span id="selectedPlanLabel" class="fw-bold text-primary-300"></span></p>
        <small class="text-white-400">In development, this updates your role immediately.</small>
      </div>
      <div class="modal-footer border-0">
        <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-custom2">Confirm</button>
      </div>
    </form>
  </div>
</div>

@push('styles')
<style>
.pricing-card { border: 0; box-shadow: 0 6px 20px rgba(0,0,0,.25); }
.ribbon { width: 120px; height: 120px; overflow: hidden; position: absolute; top: -6px; right: -6px; }
.ribbon span {
    position: absolute; display: block; width: 160px; padding: 8px 0;
    background: #a3ce14; color: #111; text-align: center; font-weight: 700;
    transform: rotate(45deg); top: 18px; right: -38px; box-shadow: 0 3px 10px rgba(0,0,0,.2);
}
.bg-black-200 { background: #1e1e1e !important; }
.text-white-400 { color: #bdbdbd !important; }
.text-primary-300 { color: var(--primary-300); }
.text-primary-500 { color: var(--primary-500); }
.btn-custom2 { background: var(--primary-500); color: #111; border: 0; }
.btn-custom2:hover { filter: brightness(1.05); }
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
@endsection
