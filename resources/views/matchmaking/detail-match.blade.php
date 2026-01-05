@extends('layouts.app')

@section('content')
<div class="container py-5 matchmaking-shell">
    <div class="row justify-content-center">
        <div class="col-xl-8">

            <div class="mb-4 text-center text-white-400">
                <p class="text-uppercase small fw-semibold mb-1">Matchmaking</p>
                <h3 class="fw-bold text-white">Match Details</h3>
                <p class="mb-0">Review player lineup, monitor games, and manage the match status.</p>
            </div>

            <div class="glass-card mb-4">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
                    <div>
                        <p class="text-uppercase small text-white-300 mb-1">Match</p>
                        <h4 class="fw-semibold text-white mb-0">{{ $match->display_title ?? ('Match #' . $match->id) }}</h4>
                        <p class="text-white-400 small mb-0">Mode {{ ucfirst($match->mode) }}</p>
                    </div>
                    <span class="status-pill {{ 'status-' . $match->status }}">
                        {{ ucfirst($match->status) }}
                    </span>
                </div>

                <div class="row g-4">
                    <div class="col-sm-6">
                        <div class="info-block">
                            <p class="label">Total Games</p>
                            <p class="value">{{ $match->games->count() }}</p>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="info-block">
                            <p class="label">Created At</p>
                            <p class="value">{{ $match->created_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="glass-card mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="text-white mb-0">Player Lineup</h5>
                    <span class="text-white-300 small">{{ $match->players->count() }} players</span>
                </div>
                <div class="player-list">
                    @foreach($match->players as $player)
                        <div class="player-item">
                            <div>
                                <p class="mb-0 fw-semibold text-white">
                                    {{ $player->user->username ? '@' . ltrim($player->user->username, '@') : $player->user->name }}
                                </p>
                                <small class="text-white-400">{{ $player->user->name }}</small>
                            </div>
                            <div class="team-pill">Team {{ $player->team }}</div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="glass-card mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="text-white mb-0">Games</h5>
                    @if(in_array($match->status, ['started', 'done']))
                        <a href="{{ route('matchmaking.match.createGame', $match->id) }}" class="btn btn-custom btn-sm px-3">+ Add Game</a>
                    @endif
                </div>

                @if($match->games->isEmpty())
                    <div class="empty-state text-center">
                        <i class="bi bi-joystick text-primary-300 fs-3 mb-2"></i>
                        <p class="mb-0 text-white-400">No games have been logged yet.</p>
                    </div>
                @else
                    <div class="game-grid">
                        @foreach($match->games->sortBy('game_number') as $g)
                            <div class="game-card">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-uppercase small text-white-300">Game {{ $g->game_number }}</span>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('matchmaking.match.editGame', [$match->id, $g->id]) }}" class="btn btn-outline-light btn-sm">Edit</a>
                                        <form action="{{ route('matchmaking.match.deleteGame', [$match->id, $g->id]) }}" method="POST" onsubmit="return confirm('Delete this game?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-outline-danger btn-sm">Delete</button>
                                        </form>
                                    </div>
                                </div>
                                <div class="score-display">
                                    <span>{{ $g->team1_score }}</span>
                                    <span class="divider">-</span>
                                    <span>{{ $g->team2_score }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="glass-card mb-4">
                <div class="d-grid gap-3">
                    @if(in_array($match->status, ['started', 'matched']))
                        <form action="{{ route('matchmaking.match.cancel', $match->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-danger w-100 py-3 fw-semibold">
                                Cancel Match
                            </button>
                        </form>
                    @endif

                    @if($match->status === 'matched')
                        <form action="{{ route('matchmaking.match.start', $match->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success w-100 py-3 fw-semibold">
                                Start Match
                            </button>
                        </form>
                    @endif

                    @if($match->status === 'started')
                        <form action="{{ route('matchmaking.match.finish', $match->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary w-100 py-3 fw-semibold">
                                Mark Match Finished
                            </button>
                        </form>
                    @endif
                </div>
            </div>

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
        background: rgba(255, 255, 255, 0.03);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border-radius: 24px;
        padding: 2rem;
        border: 1px solid rgba(255, 255, 255, 0.06);
        box-shadow: 0 25px 60px rgba(0, 0, 0, 0.5);
    }
    .info-block,
    .player-item,
    .game-card {
        background: rgba(255, 255, 255, 0.03);
        border-radius: 18px;
        border: 1px solid rgba(255, 255, 255, 0.08);
    }
    .info-block {
        padding: 1rem 1.25rem;
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
    .status-matched { background: rgba(128, 192, 255, 0.2); color: var(--info); }
    .status-started { background: rgba(163, 206, 20, 0.2); color: var(--primary-300); }
    .status-done { background: rgba(163, 206, 20, 0.18); color: #c6ff7e; }
    .status-cancelled { background: rgba(255, 255, 255, 0.12); color: var(--white-400); }

    .player-list { display: flex; flex-direction: column; gap: 0.85rem; }
    .player-item { padding: 1rem 1.25rem; display: flex; justify-content: space-between; align-items: center; }
    .team-pill { padding: 0.35rem 0.9rem; border-radius: 999px; background: rgba(255, 255, 255, 0.1); font-size: 0.8rem; }

    .game-grid { display: flex; flex-direction: column; gap: 1rem; }
    .game-card { padding: 1.25rem; }
    .score-display { font-size: 2rem; font-weight: 700; display: flex; align-items: center; gap: 0.75rem; color: var(--white-500); }
    .score-display .divider { color: var(--primary-300); font-size: 1.5rem; }

    .empty-state { padding: 2rem 1rem; background: rgba(255, 255, 255, 0.02); border-radius: 18px; border: 1px dashed rgba(255, 255, 255, 0.2); }

    @media (max-width: 576px) {
        .glass-card { padding: 1.5rem; }
        .player-item { flex-direction: column; align-items: flex-start; gap: 0.5rem; }
        .score-display { font-size: 1.6rem; }
    }
</style>
@endpush

@endsection
