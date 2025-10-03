@extends('layouts.app')
@section('content')
    <div class="max-w-md mx-auto my-40 bg-white p-6 rounded-lg shadow">
        <div class="mb-6">
            <h2 class="text-2xl font-semibold">Lupa Password</h2>
        </div>
        @if (session('status'))
            <div class="mb-3 text-green-600">{{ session('status') }}</div>
        @endif
        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <label class="block text-sm font-medium text-slate-800">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required autocomplete="off"
                class="mt-1 block w-full rounded-xl border-line focus:border-brand-400 focus:ring-brand-300 text-sm @error('email') @enderror">
            @error('email')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
            <button
                class="w-full mt-6 inline-flex justify-center rounded-xl bg-brand-700 hover:bg-brand-600 text-white text-sm px-4 py-2.5 disabled:opacity-60 disabled:cursor-not-allowed">Kirim
                Link Reset</button>
        </form>
    </div>
@endsection
