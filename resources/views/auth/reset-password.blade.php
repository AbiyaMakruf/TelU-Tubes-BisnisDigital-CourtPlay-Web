@extends('layouts.app')

@section('title', 'Reset Password')

@section('content')
<div class="container-fluid d-flex align-items-center justify-content-center min-vh-100 bg-black-300">
    <div class="card shadow-lg border-0 bg-black-200 text-white px-5 py-4" style="max-width: 600px; width: 100%;">
        <div class="text-center mb-4">
            <img src="{{ asset('assets/Logo Vertical.svg') }}" alt="CourtPlay Logo" width="120" class="mb-3">
            <h2 class="fw-bold text-primary-500">Reset Password</h2>
            <p class="text-white-400 mb-0">Enter your new password below</p>
        </div>

        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="mb-3">
                <label for="email" class="form-label text-primary-500">Email Address</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    class="form-control input-custom bg-primary-500 text-black-300"
                    value="{{ old('email') }}"
                    required
                    autofocus
                >
            </div>

            <div class="mb-3">
                <label for="password" class="form-label text-primary-500">New Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="form-control input-custom bg-primary-500 text-black-300"
                    required
                >
            </div>

            <div class="mb-4">
                <label for="password_confirmation" class="form-label text-primary-500">Confirm Password</label>
                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    class="form-control input-custom bg-primary-500 text-black-300"
                    required
                >
            </div>

            <button type="submit" class="btn btn-custom2 w-100 py-2 fs-5 fw-semibold">
                Reset Password
            </button>
        </form>
    </div>
</div>

@push('scripts')
<script>
(function () {
    @if(session('toastr'))
        var n = @json(session('toastr'));
        if (Array.isArray(n)) {
            n.forEach(function(item){
                if (item && item.type && item.message && typeof toastr[item.type] === 'function') {
                    toastr[item.type](item.message, item.title || '', item.options || {});
                }
            });
        } else if (n && n.type && n.message && typeof toastr[n.type] === 'function') {
            toastr[n.type](n.message, n.title || '', n.options || {});
        }
    @endif

    @if(session('success'))
        toastr.success(@json(session('success')));
    @endif

    @if(session('error'))
        toastr.error(@json(session('error')));
    @endif

    @if($errors->any())
        toastr.error(@json($errors->first()));
    @endif
})();
</script>
@endpush
@endsection
