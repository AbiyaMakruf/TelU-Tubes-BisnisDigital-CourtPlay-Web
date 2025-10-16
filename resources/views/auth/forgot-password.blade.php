@extends('layouts.app')

@section('title', 'Forgot Password')

@section('content')
<div class="container py-5 d-flex justify-content-center">
    <div class="card p-4 shadow bg-black-200 text-white" style="max-width: 450px;">
        <h4 class="fw-bold text-primary-500 mb-3 text-center">Forgot Password</h4>
        <p class="text-white-400 text-center">Enter your email address to receive a reset link.</p>

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input
                    type="email"
                    id="email"
                    class="form-control input-custom"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                >
            </div>
            <button type="submit" class="btn btn-custom2 w-100">Send Reset Link</button>
        </form>

        <div class="text-center mt-3">
            <a href="{{ route('login') }}" class="text-primary-500">Back to login</a>
        </div>
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
        toastr.error(@json($errors->first('email') ?? $errors->first()));
    @endif
})();
</script>
@endpush
@endsection
