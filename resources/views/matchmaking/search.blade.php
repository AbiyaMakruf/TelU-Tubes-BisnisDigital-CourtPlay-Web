@extends('layouts.app')

@section('content')
<div class="container py-4" style="max-width: 650px;">

    <h3 class="fw-bold mb-4">Buat Pencarian Matchmaking</h3>

    <form method="POST" action="{{ route('matchmaking.search.store') }}">
        @csrf

        {{-- COURT --}}
        <div class="mb-3">
            <label class="form-label fw-semibold">Pilih Court</label>
            <select class="form-select" name="court_id" required>
                <option value="">-- Pilih Court --</option>
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
            <label class="form-label fw-semibold">Mode Permainan</label>
            <select class="form-select" name="play_mode" required>
                <option value="single">Single (1 vs 1)</option>
                <option value="double">Double (2 vs 2)</option>
            </select>
        </div>

        {{-- DATE --}}
        <div class="mb-3">
            <label class="form-label fw-semibold">Tanggal Bermain</label>
            <input type="date" class="form-control" name="play_date" required>
        </div>

        {{-- TIME START --}}
        <div class="mb-3">
            <label class="form-label fw-semibold">Jam Mulai</label>
            <input type="time" class="form-control" name="play_time_start" required>
        </div>

        {{-- TIME END --}}
        <div class="mb-4">
            <label class="form-label fw-semibold">Jam Selesai</label>
            <input type="time" class="form-control" name="play_time_end" required>
        </div>

        <div class="d-flex justify-content-between">
            <a href="{{ route('matchmaking.index') }}" class="btn btn-outline-secondary">
                Kembali
            </a>

            <button type="submit" class="btn btn-primary">
                Buat Pencarian
            </button>
        </div>
    </form>
</div>
@endsection
