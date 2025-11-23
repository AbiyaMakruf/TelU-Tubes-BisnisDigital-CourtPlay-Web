@extends('layouts.app')

@section('content')
<div class="container py-4" style="max-width: 650px;">

    <h3 class="fw-bold mb-4">Detail Pencarian</h3>

    <div class="card shadow-sm mb-3">
        <div class="card-body">

            <h4 class="fw-semibold">{{ $search->court?->name }}</h4>

            <p class="mb-1 text-muted">
                Mode: {{ ucfirst($search->play_mode) }}
            </p>

            <p class="mb-1">
                <strong>Tanggal:</strong> {{ $search->play_date }}
            </p>

            <p class="mb-3">
                <strong>Jam:</strong>
                {{ $search->play_time_start }} —
                {{ $search->play_time_end }}
            </p>

            <p>
                <strong>Status:</strong>
                <span class="badge bg-primary">
                    {{ ucfirst($search->status) }}
                </span>
            </p>

        </div>
    </div>

    @if($search->status === 'searching')
        <form action="{{ route('matchmaking.search.cancel', $search->id) }}" method="POST" class="mb-3">
            @csrf
            <button type="submit" class="btn btn-danger w-100">
                Batalkan Pencarian
            </button>
        </form>
    @endif

    <a href="{{ route('matchmaking.index') }}" class="btn btn-secondary">
        Kembali
    </a>

</div>
@endsection
