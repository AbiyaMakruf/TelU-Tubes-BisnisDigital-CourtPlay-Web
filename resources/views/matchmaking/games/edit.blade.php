@extends('layouts.app')

@section('content')
<div class="container py-5 matchmaking-shell">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-xl-5">

            <div class="mb-4 text-center text-white-400">
                <p class="text-uppercase small fw-semibold mb-1">Matchmaking</p>
                <h3 class="fw-bold text-white">Edit Game {{ $game->game_number }}</h3>
                <p class="mb-0">Match {{ $match->display_title ?? ('#' . $match->id) }}</p>
            </div>

            <div class="glass-card form-card">
                <form action="{{ route('matchmaking.match.updateGame', [$match->id, $game->id]) }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Game Number</label>
                        <input type="number" name="game_number" class="form-control"
                            value="{{ $game->game_number }}" min="1" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Team 1 Score</label>
                        <input type="number" name="team1_score" class="form-control"
                               value="{{ $game->team1_score }}" min="0" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Team 2 Score</label>
                        <input type="number" name="team2_score" class="form-control"
                               value="{{ $game->team2_score }}" min="0" required>
                    </div>

                    <div class="d-grid gap-3 mt-4">
                        <button class="btn btn-custom w-100 py-3 fw-semibold">Update Game</button>
                        <a href="{{ route('matchmaking.match.detail', $match->id) }}" class="btn btn-outline-light w-100 py-3">
                            Back to Match Details
                        </a>
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
        background: rgba(15, 15, 15, 0.92);
        border-radius: 24px;
        padding: 2rem;
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 25px 60px rgba(0, 0, 0, 0.65);
    }
    .form-card .form-label {
        color: var(--white-300);
        font-weight: 600;
        letter-spacing: 0.03em;
        text-transform: uppercase;
        font-size: 0.78rem;
    }
    .form-card .form-control {
        background: rgba(255, 255, 255, 0.04);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 16px;
        color: var(--white-500);
        padding: 0.85rem 1rem;
    }
    .form-card .form-control:focus {
        border-color: rgba(var(--primary-300-rgb), 0.8);
        box-shadow: 0 0 0 0.2rem rgba(var(--primary-300-rgb), 0.2);
        color: var(--white-500);
    }
    @media (max-width: 576px) {
        .glass-card { padding: 1.5rem; }
    }
</style>
@endpush

@endsection
