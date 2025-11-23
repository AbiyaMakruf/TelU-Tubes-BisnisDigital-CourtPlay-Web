@extends('layouts.app')

@section('content')
<div class="container py-5 matchmaking-page">

    <div class="row align-items-center gy-3 mb-4">
        <div class="col-lg-8">
            <p class="text-white-400 mb-1 text-uppercase small fw-semibold">Play smarter</p>
            <h2 class="fw-bold text-white mb-2">Matchmaking Hub</h2>
            <p class="text-white-400 mb-0">Organize your searches, build matches, and stay on top of every update in one sleek, unified space.</p>
        </div>
        <div class="col-lg-4 text-lg-end">
            <div class="d-grid d-lg-inline-flex justify-content-lg-end">
                <a href="{{ route('matchmaking.search.create') }}" class="btn btn-custom px-4 py-2 cta-btn">
                + New Search
                </a>
            </div>
        </div>
    </div>

    {{-- ===========================
         Combined history (search + match)
    ============================ --}}
    @if($history->isEmpty())
        <div class="card border-0 shadow-sm text-center py-5 bg-black-200 text-white-400">
            <div class="card-body py-4">
                <div class="mb-3">
                    <i class="bi bi-people fs-1 text-primary-300"></i>
                </div>
                <h5 class="fw-semibold text-white mb-2">No matchmaking activity yet</h5>
                <p class="text-white-400 mb-4">Kick things off by creating a search and let us help you find the perfect playing partner.</p>
                <a href="{{ route('matchmaking.search.create') }}" class="btn btn-custom px-4">
                    Create Your First Search
                </a>
            </div>
        </div>
    @else
        @php
            $statusColorMap = [
                'searching' => 'badge-warning',
                'matched'   => 'badge-info',
                'started'   => 'badge-primary',
                'done'      => 'badge-success',
                'cancelled' => 'badge-secondary',
            ];
        @endphp

        <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-4">
            @foreach($history as $item)
                @php
                    $isSearch = $item->type === 'search';
                    $targetUrl = $isSearch
                        ? route('matchmaking.search.detail', $item->id)
                        : route('matchmaking.match.detail', $item->id);

                    $statusKey = $item->status ?? null;
                    $statusColor = $statusColorMap[$statusKey] ?? 'secondary';

                    $latestGame = $isSearch ? null : $item->games->sortByDesc('game_number')->first();
                @endphp

                <div class="col">
                    <div class="matchmaking-card card border-0 shadow-sm h-100 position-relative text-white-500">
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-start mb-3 flex-wrap gap-2">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="chip {{ $isSearch ? 'chip-primary' : 'chip-dark' }}">
                                        {{ $isSearch ? 'Search' : 'Match' }}
                                    </span>
                                    <span class="text-white-400 small">
                                        {{ $item->created_at->format('d M Y') }}
                                    </span>
                                </div>
                                <span class="status-pill {{ $statusColor }}">
                                    {{ ucfirst($statusKey ?? 'unknown') }}
                                </span>
                            </div>

                            @if($isSearch)
                                <h5 class="fw-semibold text-white mb-1">{{ $item->court?->name ?? 'Court unavailable' }}</h5>
                                <p class="text-white-400 small mb-3">Mode {{ ucfirst($item->play_mode) }}</p>

                                @if(!empty($item->display_owner))
                                    <p class="text-white-400 small mb-3 d-flex align-items-center gap-2">
                                        <i class="bi bi-person-circle text-primary-300"></i>
                                        {{ $item->display_owner }}
                                    </p>
                                @endif

                                <ul class="list-unstyled mb-0 small text-white-400">
                                    <li class="mb-1 d-flex align-items-center gap-2">
                                        <i class="bi bi-calendar-event text-primary-300"></i>
                                        {{ $item->play_date }}
                                    </li>
                                    <li class="d-flex align-items-center gap-2">
                                        <i class="bi bi-clock text-primary-300"></i>
                                        {{ $item->play_time_start }} – {{ $item->play_time_end }}
                                    </li>
                                </ul>
                            @else
                                <h5 class="fw-semibold text-white mb-1">{{ $item->display_title ?? 'Match #' . $item->id }}</h5>
                                <p class="text-white-400 small mb-3">Mode {{ ucfirst($item->mode) }}</p>

                                <div class="mb-3">
                                    <div class="text-uppercase text-white-300 fw-semibold small mb-1">Players</div>
                                    <ul class="list-unstyled mb-0 small text-white-400">
                                        @foreach($item->players as $player)
                                            <li class="d-flex justify-content-between gap-2">
                                                <span class="text-truncate">
                                                    {{ $player->user->username ? '@' . ltrim($player->user->username, '@') : $player->user->name }}
                                                </span>
                                                <span class="text-white-300">T{{ $player->team }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>

                                @if($latestGame)
                                    <div class="latest-game small fw-semibold">
                                        Game {{ $latestGame->game_number }} · {{ $latestGame->team1_score }} - {{ $latestGame->team2_score }}
                                    </div>
                                @endif
                            @endif

                            <div class="mt-4 pt-3 d-flex justify-content-between align-items-center border-top border-white-100 flex-wrap gap-2">
                                <small class="text-white-400">
                                    Updated {{ $item->created_at->diffForHumans() }}
                                </small>
                                <span class="fw-semibold text-primary-300">
                                    View details <i class="bi bi-arrow-right-short"></i>
                                </span>
                            </div>
                            <a href="{{ $targetUrl }}" class="stretched-link"></a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

</div>
@endsection

@push('styles')
<style>
    .matchmaking-page {
        color: var(--white-500);
    }
    .matchmaking-page .chip {
        padding: 0.35rem 0.85rem;
        border-radius: 999px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }
    .matchmaking-page .chip-primary {
        background: rgba(var(--primary-300-rgb), 0.15);
        color: var(--primary-300);
        border: 1px solid rgba(var(--primary-300-rgb), 0.4);
    }
    .matchmaking-page .chip-dark {
        background: rgba(255, 255, 255, 0.08);
        color: var(--white-400);
        border: 1px solid rgba(255, 255, 255, 0.15);
    }
    .matchmaking-card {
        background: linear-gradient(140deg, rgba(33,33,33,0.95), rgba(12,12,12,0.95));
        border: 1px solid rgba(255, 255, 255, 0.04) !important;
        transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
        border-radius: 1.25rem;
        overflow: hidden;
    }
    .matchmaking-card:hover {
        transform: translateY(-4px);
        border-color: rgba(var(--primary-300-rgb), 0.4) !important;
        box-shadow: 0 20px 45px rgba(0, 0, 0, 0.55);
    }
    .matchmaking-page .status-pill {
        padding: 0.35rem 0.9rem;
        border-radius: 999px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }
    .matchmaking-page .badge-warning {
        background: rgba(244, 237, 98, 0.2);
        color: var(--warning);
    }
    .matchmaking-page .badge-info {
        background: rgba(128, 192, 255, 0.2);
        color: var(--info);
    }
    .matchmaking-page .badge-primary {
        background: rgba(var(--primary-300-rgb), 0.2);
        color: var(--primary-300);
    }
    .matchmaking-page .badge-success {
        background: rgba(163, 206, 20, 0.15);
        color: #c6ff7e;
    }
    .matchmaking-page .badge-secondary {
        background: rgba(255, 255, 255, 0.1);
        color: var(--white-300);
    }
    .matchmaking-page .latest-game {
        background: rgba(255, 255, 255, 0.06);
        border-radius: 0.75rem;
        padding: 0.55rem 0.85rem;
        color: var(--white-300);
        border: 1px solid rgba(255, 255, 255, 0.12);
    }
    .matchmaking-page .cta-btn {
        width: 100%;
    }
    @media (min-width: 992px) {
        .matchmaking-page .cta-btn {
            width: auto;
        }
    }
    @media (max-width: 768px) {
        .matchmaking-page h2 {
            font-size: 1.75rem;
        }
        .matchmaking-page .card-body {
            padding: 1.35rem;
        }
    }
    @media (max-width: 576px) {
        .matchmaking-page .chip,
        .matchmaking-page .status-pill {
            font-size: 0.7rem;
        }
        .matchmaking-page .row > .col-lg-4 .btn {
            width: 100%;
        }
    }
</style>
@endpush
