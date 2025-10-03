@extends('layouts.app')
@section('content')
    <div class="max-w-md mx-auto my-40 bg-white p-6 rounded-lg shadow">
        <div class="mb-6">
            <h2 class="text-2xl font-semibold">Reset Password</h2>
        </div>
        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <label class="block text-sm font-medium text-slate-800 mb-1 mt-3">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required
                class="mt-1 block w-full rounded-xl border-line focus:border-brand-400 focus:ring-brand-300 text-sm @error('email') @enderror">
            @error('email')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror

            <label class="block text-sm font-medium text-slate-800 mb-1 mt-3">Password Baru</label>
            <input type="password" name="password" required minlength="8"
                class="mt-1 block w-full rounded-xl border-line focus:border-brand-400 focus:ring-brand-300 text-sm @error('password') @enderror">
            @error('password')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror

            <label class="block text-sm font-medium text-slate-800 mb-1 mt-3">Konfirmasi Password</label>
            <input type="password" name="password_confirmation" required minlength="8"
                class="mt-1 block w-full rounded-xl border-line focus:border-brand-400 focus:ring-brand-300 text-sm">

            <button
                class="w-full mt-6 inline-flex justify-center rounded-xl bg-brand-700 hover:bg-brand-600 text-white text-sm px-4 py-2.5 disabled:opacity-60 disabled:cursor-not-allowed">Reset
                Password</button>
        </form>
    </div>
@endsection
