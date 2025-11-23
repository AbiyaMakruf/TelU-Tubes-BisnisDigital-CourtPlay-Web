@extends('layouts.app')

@section('content')
<div class="container my-4" style="max-width: 600px;">

    <h3 class="fw-bold mb-3">Tambah Game pada Match #{{ $match->id }}</h3>

    <form action="{{ route('matchmaking.match.storeGame', $match->id) }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label fw-semibold">Skor Team 1</label>
            <input type="number" name="team1_score" class="form-control" min="0" required>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Skor Team 2</label>
            <input type="number" name="team2_score" class="form-control" min="0" required>
        </div>

        <button class="btn btn-primary w-100">Simpan Game</button>

        <a href="{{ route('matchmaking.match.detail', $match->id) }}"
           class="btn btn-secondary w-100 mt-2">
            Kembali
        </a>

    </form>
</div>
@endsection
