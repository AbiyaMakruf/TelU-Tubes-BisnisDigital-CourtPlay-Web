@extends('layouts.app')

@section('content')
<div class="container py-4" style="max-width: 700px;">

    <h3 class="fw-bold mb-4">Detail Match</h3>

    <div class="card shadow-sm mb-3">
        <div class="card-body">

            {{-- MATCH HEADER --}}
            <h4 class="fw-semibold">Match ID: {{ $match->id }}</h4>

            <p class="text-muted mb-1">Mode: {{ ucfirst($match->mode) }}</p>

            <p class="mb-3">
                <strong>Status:</strong>
                <span class="badge bg-info">{{ ucfirst($match->status) }}</span>
            </p>

            <hr>

            {{-- PLAYERS --}}
            <h5 class="fw-semibold">Pemain:</h5>
            @foreach($match->players as $player)
                <p class="mb-1">
                    {{ $player->user->name }}
                    <span class="text-muted">(T{{ $player->team }})</span>
                </p>
            @endforeach

            <hr>

            {{-- GAMES --}}
            <h5 class="fw-semibold">Games:</h5>

            @if($match->games->count() == 0)
                <p class="text-muted">Belum ada game</p>
            @else
                @foreach($match->games->sortBy('game_number') as $g)

                    <div class="d-flex justify-content-between align-items-center mb-2 p-2 border rounded">

                        <div>
                            <strong>Game {{ $g->game_number }}</strong>:
                            {{ $g->team1_score }} - {{ $g->team2_score }}
                        </div>

                        <div class="d-flex">

                            {{-- EDIT GAME --}}
                            <a href="{{ route('matchmaking.match.editGame', [$match->id, $g->id]) }}"
                                class="btn btn-sm btn-outline-primary me-2">
                                Edit
                            </a>

                            {{-- DELETE GAME --}}
                            <form action="{{ route('matchmaking.match.deleteGame', [$match->id, $g->id]) }}"
                                method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">
                                    Hapus
                                </button>
                            </form>

                        </div>
                    </div>
                @endforeach
            @endif

        </div>
    </div>

    {{-- CANCEL MATCH (HANYA JIKA MATCHED) --}}
    @if(in_array($match->status, ['started', 'matched']))
        <form action="{{ route('matchmaking.match.cancel', $match->id) }}"
            method="POST" class="mb-3">
            @csrf
            <button type="submit" class="btn btn-danger w-100">
                Batalkan Match
            </button>
        </form>
    @endif

    @if($match->status === 'matched')
        <form action="{{ route('matchmaking.match.start', $match->id) }}"
            method="POST" class="mb-3">
            @csrf
            <button type="submit" class="btn btn-success w-100">
                Mulai game
            </button>
        </form>
    @endif


    {{-- ADD GAME (MATCHED / DONE) --}}
    @if(in_array($match->status, ['started', 'done']))
        <a href="{{ route('matchmaking.match.createGame', $match->id) }}"
           class="btn btn-primary w-100 mb-3">
           + Tambah Game
        </a>
    @endif


    {{-- FINISH MATCH (HANYA MATCHED) --}}
    @if($match->status === 'started')
        <form action="{{ route('matchmaking.match.finish', $match->id) }}"
            method="POST" class="mb-4">
            @csrf
            <button type="submit" class="btn btn-success w-100">
                ✓ Tandai Match Selesai
            </button>
        </form>
    @endif

    <a href="{{ route('matchmaking.index') }}" class="btn btn-secondary">
        Kembali
    </a>

</div>
@endsection
