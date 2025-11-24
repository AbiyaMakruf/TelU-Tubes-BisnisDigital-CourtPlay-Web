@extends('layouts.app')

@section('title', 'Login')
@section('fullbleed', true)

@push('styles')
<style>
    .auth-hero {
        position: relative;
        min-height: 100vh;
        display: flex;
        align-items: center;
        padding: 80px 0;
        overflow: hidden;
    }
    
    .auth-hero::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 100%;
        height: 100%;
        background: radial-gradient(circle at center, rgba(var(--primary-300-rgb), 0.15) 0%, transparent 70%);
        z-index: -1;
    }

    .glass-card {
        background: rgba(255, 255, 255, 0.03);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 24px;
        padding: 3rem;
        box-shadow: 0 20px 40px rgba(0,0,0,0.3);
    }

    .form-control {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: white;
        border-radius: 12px;
        padding: 12px 16px;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        background: rgba(255, 255, 255, 0.1);
        border-color: var(--primary-300);
        color: white;
        box-shadow: 0 0 0 4px rgba(var(--primary-300-rgb), 0.1);
    }

    .form-label {
        color: var(--white-300);
        font-size: 0.9rem;
        margin-bottom: 8px;
        font-weight: 500;
    }

    .toggle-password {
        position: absolute;
        right: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--white-400);
        cursor: pointer;
        transition: color 0.3s ease;
    }

    .toggle-password:hover {
        color: var(--primary-300);
    }

    .auth-link {
        color: var(--primary-300);
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .auth-link:hover {
        color: #b4e61a;
        text-decoration: underline;
    }
</style>
@endpush

@section('content')
<div class="auth-hero">
    <div class="container">
        <div class="row align-items-center justify-content-center">

            <div class="col-lg-5 text-center mb-5 mb-lg-0 d-none d-lg-block">
                <div class="position-relative">
                    <div class="position-absolute top-50 start-50 translate-middle w-75 h-75 bg-primary-300 rounded-circle" style="filter: blur(80px); opacity: 0.15; z-index: -1;"></div>
                    <img src="{{ asset('assets/Logo Vertical.svg') }}" alt="Court Play" class="img-fluid" style="max-height: 400px; filter: drop-shadow(0 0 30px rgba(0,0,0,0.5));">
                </div>
            </div>

            <div class="col-lg-5 offset-lg-1">
                <div class="glass-card">
                    <div class="text-center mb-4 d-lg-none">
                        <img src="{{ asset('assets/Logo.svg') }}" alt="CourtPlay" height="40">
                    </div>
                    
                    <div class="mb-4">
                        <h2 class="fw-bold text-white mb-2">Welcome Back</h2>
                        <p class="text-white-400">
                            Don't have an account? 
                            <a href="{{ route('signup') }}" class="auth-link">Sign up</a>
                        </p>
                    </div>

                    <form method="POST" action="{{ route('login.post') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="email" class="form-label">Email or Username</label>
                            <input
                                type="text"
                                id="email"
                                name="email"
                                class="form-control"
                                value="{{ old('email') }}"
                                placeholder="Enter your email or username"
                                required
                                autofocus
                            >
                        </div>

                        <div class="mb-4 password-wrapper">
                            <label for="password" class="form-label">Password</label>
                            <div class="position-relative">
                                <input
                                    type="password"
                                    id="password"
                                    name="password"
                                    class="form-control"
                                    placeholder="Enter your password"
                                    required
                                    autocomplete="current-password"
                                >
                                <i class="bi bi-eye toggle-password"
                                    data-toggle-password
                                    role="button"
                                    aria-label="Show password"
                                    tabindex="0"></i>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input class="form-check-input bg-transparent border-secondary" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label text-white-400 small" for="remember">
                                    Remember me
                                </label>
                            </div>
                            <a href="{{ route('password.request') }}" class="text-white-400 small text-decoration-none hover-primary">
                                Forgot password?
                            </a>
                        </div>

                        <button type="submit" class="btn btn-custom2 w-100 py-3 fw-bold">Sign In</button>
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
