@extends('layouts.app')

@section('content')
<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Matchmaking</h2>

        <a href="{{ route('matchmaking.search.create') }}" class="btn btn-primary">
            + Tambah Pencarian
        </a>
    </div>

    {{-- ===========================
         GABUNGAN HISTORY (search + match)
    ============================ --}}
    @if($history->count() == 0)
        <p class="text-muted">Belum ada aktivitas matchmaking.</p>
    @else
        @foreach($history as $item)

            <div class="card mb-3 shadow-sm"
                style="cursor:pointer;"
                onclick="window.location='{{ $item->type === 'search'
                    ? route('matchmaking.search.detail', $item->id)
                    : route('matchmaking.match.detail', $item->id)
                }}'">
                <div class="card-body d-flex justify-content-between">

                    {{-- -------------------------
                        LEFT SIDE
                    -------------------------- --}}
                    <div>
                        {{-- Jika item adalah SEARCH --}}
                        @if($item->type === 'search')

                            <h5 class="fw-semibold mb-1">
                                Pencarian — {{ $item->court?->name ?? 'Court Tidak Ditemukan' }}
                            </h5>

                            <div class="text-muted small mb-1">
                                Mode: {{ ucfirst($item->play_mode) }}
                            </div>

                            <div>
                                <strong>{{ $item->play_date }}</strong><br>
                                <small>{{ $item->play_time_start }} – {{ $item->play_time_end }}</small>
                            </div>

                        @endif


                        {{-- Jika item adalah MATCH --}}
                        @if($item->type === 'match')

                            <h5 class="fw-semibold mb-1">
                                Match — #{{ $item->id }}
                            </h5>

                            <div class="text-muted small">
                                Mode: {{ ucfirst($item->mode) }}
                            </div>

                            <div class="mt-2">
                                <strong>Pemain:</strong><br>
                                @foreach($item->players as $p)
                                    {{ $p->user->name }}
                                    <span class="text-muted">(T{{ $p->team }})</span><br>
                                @endforeach
                            </div>

                            @if($item->games->count() > 0)
                                <div class="mt-2">
                                    <strong>Games:</strong><br>
                                    @foreach($item->games as $g)
                                        Game {{ $g->game_number }}:
                                        {{ $g->team1_score }} - {{ $g->team2_score }} <br>
                                    @endforeach
                                </div>
                            @endif

                        @endif

                        <div class="mt-2 text-muted small">
                            Dibuat: {{ $item->created_at->diffForHumans() }}
                        </div>
                    </div>


                    {{-- -------------------------
                        RIGHT STATUS
                    -------------------------- --}}
                    <div class="text-end">
                        @php
                            $status = $item->status ?? null;

                            if ($item->type === 'match') {
                                $status = $item->status; // match status
                            }

                            $color = [
                                'searching' => 'warning',
                                'matched'   => 'info',
                                'done'      => 'success',
                                'cancelled' => 'secondary',
                            ][$status] ?? 'secondary';
                        @endphp

                        <span class="badge bg-{{ $color }} px-3 py-2 fs-6">
                            {{ ucfirst($status) }}
                        </span>
                    </div>

                </div>
            </div>

        @endforeach
    @endif

</div>
@endsection
