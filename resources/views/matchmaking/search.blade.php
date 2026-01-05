@extends('layouts.app')

@section('content')
<div class="container py-5 matchmaking-shell">
    <div class="row justify-content-center">
        <div class="col-xl-6 col-lg-7">

            <div class="mb-4 text-center text-white-400">
                <p class="text-uppercase small fw-semibold mb-1">Matchmaking</p>
                <h3 class="fw-bold text-white">Create a New Search</h3>
                <p class="mb-0">Pick a court, mode, and time slot so we can find the right opponent for you.</p>
            </div>

            <div class="glass-card form-card">
                <form method="POST" action="{{ route('matchmaking.search.store') }}">
                    @csrf

                    {{-- COURT --}}
                    <div class="mb-3">
                        <label class="form-label">Choose Court</label>
                        <select class="form-select" name="court_id" required>
                            <option value="">-- Select Court --</option>
                            @foreach($courts as $court)
                                <option value="{{ $court->id }}">
                                    {{ $court->name }}
                                    @if($court->city) - {{ $court->city }} @endif
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- MODE (single / double) --}}
                    <div class="mb-3">
                        <label class="form-label">Play Mode</label>
                        <select class="form-select" name="play_mode" required>
                            <option value="single">Single (1 vs 1)</option>
                            <option value="double">Double (2 vs 2)</option>
                        </select>
                    </div>

                    <div class="row g-3">
                        <div class="col-sm-12">
                            <label class="form-label">Play Date</label>
                            <input type="date" class="form-control" name="play_date" required>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label">Start Time</label>
                            <input type="time" class="form-control" name="play_time_start" required>
                        </div>
                        <div class="col-sm-6">
                            <label class="form-label">End Time</label>
                            <input type="time" class="form-control" name="play_time_end" required>
                        </div>
                    </div>

                    <div class="d-flex flex-column flex-sm-row gap-3 mt-4">
                        <a href="{{ route('matchmaking.index') }}" class="btn btn-outline-light w-100 py-3">
                            Back
                        </a>

                        <button type="submit" class="btn btn-custom w-100 py-3 fw-semibold">
                            Create Search
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

@push('styles')
<style>
    .matchmaking-shell { color: var(--white-500); }
    .glass-card {
        background: rgba(255, 255, 255, 0.03);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border-radius: 24px;
        padding: 2rem;
        border: 1px solid rgba(255, 255, 255, 0.06);
        box-shadow: 0 25px 60px rgba(0, 0, 0, 0.5);
    }
    .form-card .form-label {
        color: var(--white-300);
        font-weight: 600;
        letter-spacing: 0.03em;
        text-transform: uppercase;
        font-size: 0.78rem;
    }
    .form-card .form-select,
    .form-card .form-control {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 16px;
        color: var(--white-500);
        padding: 0.85rem 1rem;
        transition: all 0.2s ease;
    }
    .form-card .form-select:focus,
    .form-card .form-control:focus {
        background: rgba(255, 255, 255, 0.08);
        border-color: rgba(var(--primary-300-rgb), 0.5);
        box-shadow: 0 0 0 0.25rem rgba(var(--primary-300-rgb), 0.15);
        color: var(--white-500);
    }
    option { color: #111; }
    @media (max-width: 576px) {
        .glass-card { padding: 1.5rem; }
    }
</style>
@endpush

@endsection
