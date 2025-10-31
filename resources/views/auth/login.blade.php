@extends('layouts.app')

@section('title', 'Login')
@section('fullbleed', true)

@section('content')
<div class="login-page d-flex align-items-center my-auto py-5">
    <div class="container">
        <div class="row align-items-center">

            <div class="col-lg-6 text-center justify-content-center mb-5 mb-lg-0" >
                <img src="{{ asset('assets/Logo Vertical.svg') }}" alt="Court Play" id="login-logo-section" class="mb-4" >
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

                        <div class="mb-3 password-wrapper position-relative">
                        <label for="password" class="form-label text-primary-500">Password</label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="form-control input-custom"
                            required
                            autocomplete="current-password"
                        >
                        <i class="bi bi-eye toggle-password"
                            data-toggle-password
                            role="button"
                            aria-label="Show password"
                            tabindex="0"></i>
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
@endsection

@push('scripts')
<script>
(function attachPwToggles(){
  function toggleFrom(btn){
    const wrap  = btn.closest('.password-wrapper');
    if(!wrap) return;
    const input = wrap.querySelector('input[type="password"], input[type="text"]');
    if(!input) return;

    const isPwd = input.getAttribute('type') === 'password';
    input.setAttribute('type', isPwd ? 'text' : 'password');
    btn.classList.toggle('bi-eye', !isPwd);
    btn.classList.toggle('bi-eye-slash', isPwd);
    btn.setAttribute('aria-label', isPwd ? 'Hide password' : 'Show password');
  }

  document.addEventListener('click', function(e){
    const btn = e.target.closest('[data-toggle-password]');
    if(btn) toggleFrom(btn);
  });
  document.addEventListener('keydown', function(e){
    const btn = e.target.closest && e.target.closest('[data-toggle-password]');
    if(btn && (e.key === 'Enter' || e.key === ' ')){
      e.preventDefault();
      toggleFrom(btn);
    }
  });

  document.querySelectorAll('[data-toggle-password]').forEach(function(btn){
    btn.classList.add('bi-eye'); btn.classList.remove('bi-eye-slash');
  });
})();
</script>
@endpush
