@extends('layouts.app')

@section('content')
<div class="container py-5 matchmaking-shell">
    <div class="row justify-content-center">
        <div class="col-lg-7">

            <div class="mb-4 text-center text-white-400">
                <p class="text-uppercase small fw-semibold mb-1">Matchmaking</p>
                <h3 class="fw-bold text-white">Search Details</h3>
                <p class="mb-0">Track the status, owner, and schedule of this matchmaking request.</p>
            </div>

            <div class="glass-card mb-4">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
                    <div>
                        <p class="text-uppercase small text-white-300 mb-1">Court</p>
                        <h4 class="fw-semibold text-white mb-0">{{ $search->court?->name ?? 'Court unavailable' }}</h4>
                        <p class="text-white-400 small mb-0">{{ ucfirst($search->play_mode) }} mode</p>
                    </div>
                    <span class="status-pill {{ 'status-' . $search->status }}">
                        {{ ucfirst($search->status) }}
                    </span>
                </div>

                <div class="row g-4">
                    <div class="col-sm-6">
                        <div class="info-block">
                            <p class="label">Date</p>
                            <p class="value">{{ $search->play_date }}</p>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="info-block">
                            <p class="label">Time Slot</p>
                            <p class="value">{{ $search->play_time_start }} – {{ $search->play_time_end }}</p>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="info-block">
                            <p class="label">Created At</p>
                            <p class="value">{{ $search->created_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="info-block">
                            <p class="label">Owner</p>
                            <p class="value">{{ $search->user?->username ? '@' . ltrim($search->user->username, '@') : ($search->user?->name ?? '-') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            @if($search->status === 'searching')
                <form action="{{ route('matchmaking.search.cancel', $search->id) }}" method="POST" class="mb-3">
                    @csrf
                    <button type="submit" class="btn btn-danger w-100 py-3 fw-semibold">
                        Cancel Search
                    </button>
                </form>
            @endif

            <a href="{{ route('matchmaking.index') }}" class="btn btn-outline-light w-100 py-3">
                Back to Matchmaking
            </a>

        </div>
    </div>
</div>

@push('styles')
<style>
    .matchmaking-shell {
        color: var(--white-500);
    }
    .glass-card {
        background: rgba(15, 15, 15, 0.9);
        border-radius: 24px;
        padding: 2rem;
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 25px 60px rgba(0, 0, 0, 0.65);
    }
    .info-block {
        background: rgba(255, 255, 255, 0.04);
        border-radius: 18px;
        padding: 1rem 1.25rem;
        border: 1px solid rgba(255, 255, 255, 0.06);
    }
    .info-block .label {
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.08em;
        color: var(--white-300);
        margin-bottom: 0.2rem;
    }
    .info-block .value {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--white-500);
    }
    .status-pill {
        padding: 0.45rem 1.25rem;
        border-radius: 999px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        font-size: 0.8rem;
    }
    .status-searching {
        background: rgba(244, 237, 98, 0.18);
        color: var(--warning);
    }
    .status-matched {
        background: rgba(128, 192, 255, 0.2);
        color: var(--info);
    }
    .status-cancelled {
        background: rgba(255, 255, 255, 0.12);
        color: var(--white-400);
    }
    .status-done {
        background: rgba(163, 206, 20, 0.18);
        color: #c6ff7e;
    }
    @media (max-width: 576px) {
        .glass-card {
            padding: 1.5rem;
        }
    }
</style>
@endpush

@endsection
