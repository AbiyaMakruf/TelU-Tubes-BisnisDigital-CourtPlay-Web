@extends('layouts.app')

@section('title', 'Login')
@section('fullbleed', true)

@section('content')
<div class="login-page d-flex min-vh-100 align-items-center">
    <div class="container">
        <div class="row align-items-center">

            <div class="col-lg-6 text-center justify-content-center mb-5 mb-lg-0">
                <img src="{{ asset('assets/Logo Vertical.svg') }}" alt="Court Play" class="mb-4" style="width: 300px;">
            </div>

            <div class="col-lg-6">
                <div class="card bg-transparent border-0 text-center">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2 class="fw-bold text-primary-500">Sign in</h2>
                        <div>
                            <span class="text-primary-500 me-2">
                                New to Court Play?
                                <a href="{{ route('signup') }}" class="text-primary-100">Sign up</a>
                            </span>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('login.post') }}" class="text-start">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label">Email or Username</label>
                            <input
                                type="text"
                                id="email"
                                name="email"
                                class="form-control input-custom"
                                value="{{ old('email') }}"
                                required
                                autofocus
                            >
                        </div>

                        <div class="mb-3 position-relative">
                            <label for="password" class="form-label text-primary-500">Password</label>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                class="form-control bg-primary-500 text-black-300 pe-5 input-custom"
                                required
                            >
                            <i class="bi bi-eye toggle-password" data-toggle="#password" style="position:absolute; right:12px; cursor:pointer;"></i>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label text-white-400" for="remember">
                                Remember me
                            </label>
                        </div>

                        <button type="submit" class="btn btn-custom2 w-100 mt-2">Sign in</button>

                        <div class="mt-3 text-center">
                            <a href="{{ route('password.request') }}" class="text-primary-500 text-decoration-none">
                                Forgot your password?
                            </a>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

@push('scripts')
<script>
(function () {
    var eye = document.querySelector('.toggle-password');
    if (eye) {
        eye.addEventListener('click', function () {
            var target = document.querySelector(this.getAttribute('data-toggle'));
            if (!target) return;
            if (target.type === 'password') {
                target.type = 'text';
                this.classList.remove('bi-eye');
                this.classList.add('bi-eye-slash');
            } else {
                target.type = 'password';
                this.classList.remove('bi-eye-slash');
                this.classList.add('bi-eye');
            }
        });
    }

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
