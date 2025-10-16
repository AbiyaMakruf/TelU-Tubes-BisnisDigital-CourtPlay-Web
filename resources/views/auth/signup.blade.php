@extends('layouts.app')

@section('title', 'Sign Up')
@section('fullbleed', true)

@section('content')
<div class="container-fluid min-vh-100 d-flex align-items-center bg-black-300">
    <div class="container">
        <div class="row align-items-center">

            <div class="col-lg-6 d-flex flex-column text-white p-5">
                <div class="text-center mb-4">
                    <img src="{{ asset('assets/Logo.svg') }}" alt="CourtPlay Logo" class="mb-4" width="300">
                    <h3 class="fw-bold mb-3 text-primary-500">Create your free basic account</h3>
                    <ul class="list-unstyled text-start d-inline-block text-white-400">
                        <li class="mb-2 text-primary-500 fs-5">✔ Free 1 video analytics</li>
                        <li class="mb-2 text-primary-500 fs-5">✔ Dashboard metrics</li>
                        <li class="mb-2 text-primary-500 fs-5">✔ AI mapping</li>
                    </ul>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card bg-transparent border-0 text-center">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2 class="fw-bold text-primary-500">Sign up</h2>
                        <div>
                            <span class="text-primary-500 me-2">
                                Already have an account?
                                <a href="{{ route('login') }}" class="text-primary-100">Sign in</a>
                            </span>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('signup') }}" class="text-start">
                        @csrf

                        <div class="mb-2">
                            <label for="first_name" class="form-label">First Name</label>
                            <input type="text" id="first_name" name="first_name" class="form-control input-custom" value="{{ old('first_name') }}" required>
                        </div>

                        <div class="mb-2">
                            <label for="last_name" class="form-label">Last Name</label>
                            <input type="text" id="last_name" name="last_name" class="form-control input-custom" value="{{ old('last_name') }}" required>
                        </div>

                        <div class="mb-2">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" id="username" name="username" class="form-control input-custom" value="{{ old('username') }}" required>
                        </div>

                        <div class="mb-2">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" id="email" name="email" class="form-control input-custom" value="{{ old('email') }}" required>
                        </div>

                        <div class="mb-2 position-relative">
                            <label for="password" class="form-label text-primary-500">Password</label>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                class="form-control bg-primary-500 text-black-300 pe-5 input-custom"
                                required
                            >
                            <i class="bi bi-eye toggle-password" data-toggle="#password" style="position:absolute; right:12px;  cursor:pointer;"></i>
                        </div>

                        <button type="submit" class="btn btn-custom2 w-100 mt-3">Sign up</button>
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
        @foreach($errors->all() as $err)
            toastr.error(@json($err));
        @endforeach
    @endif
})();
</script>
@endpush
@endsection
