@extends('layouts.app')

@section('title', 'Sign Up')
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

    .benefit-item {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 1rem;
        color: var(--white-300);
        font-size: 1.1rem;
    }

    .benefit-icon {
        width: 32px;
        height: 32px;
        background: rgba(var(--primary-300-rgb), 0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary-300);
        flex-shrink: 0;
    }
</style>
@endpush

@section('content')
<div class="auth-hero">
    <div class="container">
        <div class="row align-items-center justify-content-center g-5">

            <div class="col-lg-5 d-none d-lg-block">
                <div class="pe-lg-5">
                    <img src="{{ asset('assets/Logo.svg') }}" alt="CourtPlay Logo" class="mb-5" width="200">
                    <h1 class="fw-bold text-white mb-4 display-5">Join the Revolution</h1>
                    <p class="text-white-400 mb-5 lead">Create your free account today and start analyzing your game like a pro.</p>
                    
                    <div class="d-flex flex-column gap-2">
                        <div class="benefit-item">
                            <div class="benefit-icon"><i class="bi bi-camera-video"></i></div>
                            <span>5 Analysis Videos per month</span>
                        </div>
                        <div class="benefit-item">
                            <div class="benefit-icon"><i class="bi bi-clock"></i></div>
                            <span>10 Minutes per Video</span>
                        </div>
                        <div class="benefit-item">
                            <div class="benefit-icon"><i class="bi bi-graph-up"></i></div>
                            <span>Standard Match Analysis</span>
                        </div>
                        <div class="benefit-item">
                            <div class="benefit-icon"><i class="bi bi-cloud"></i></div>
                            <span>2GB Cloud Storage</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="glass-card">
                    <div class="text-center mb-4 d-lg-none">
                        <img src="{{ asset('assets/Logo.svg') }}" alt="CourtPlay" height="40">
                    </div>

                    <div class="mb-4">
                        <h2 class="fw-bold text-white mb-2">Create Account</h2>
                        <p class="text-white-400">
                            Already have an account? 
                            <a href="{{ route('login') }}" class="auth-link">Sign in</a>
                        </p>
                    </div>

                    <form method="POST" action="{{ route('signup') }}">
                        @csrf

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="first_name" class="form-label">First Name</label>
                                <input type="text" id="first_name" name="first_name" class="form-control" value="{{ old('first_name') }}" placeholder="John" required>
                            </div>
                            <div class="col-md-6">
                                <label for="last_name" class="form-label">Last Name</label>
                                <input type="text" id="last_name" name="last_name" class="form-control" value="{{ old('last_name') }}" placeholder="Doe" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" id="username" name="username" class="form-control" value="{{ old('username') }}" placeholder="johndoe123" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="john@example.com" required>
                        </div>

                        <div class="mb-4 password-wrapper">
                            <label for="password" class="form-label">Password</label>
                            <div class="position-relative">
                                <input
                                    type="password"
                                    id="password"
                                    name="password"
                                    class="form-control"
                                    placeholder="Create a strong password"
                                    required
                                    autocomplete="new-password"
                                >
                                <i class="bi bi-eye toggle-password"
                                    data-toggle-password
                                    role="button"
                                    aria-label="Show password"
                                    tabindex="0"></i>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-custom2 w-100 py-3 fw-bold">Create Account</button>
                        
                        <p class="text-center text-white-50 small mt-4 mb-0">
                            By signing up, you agree to our <a href="#" class="text-white-400 text-decoration-none">Terms</a> and <a href="#" class="text-white-400 text-decoration-none">Privacy Policy</a>.
                        </p>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
(function () {
  // Toggle password via delegation (kebal re-render)
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

  // Set ikon awal
  document.querySelectorAll('[data-toggle-password]').forEach(function(btn){
    btn.classList.add('bi-eye');
    btn.classList.remove('bi-eye-slash');
  });

  // Toastr flashes (biarkan seperti semula)
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
  @if(session('success')) toastr.success(@json(session('success'))); @endif
  @if(session('error'))   toastr.error(@json(session('error')));   @endif
  @if($errors->any())
    @foreach($errors->all() as $err)
      toastr.error(@json($err));
    @endforeach
  @endif
})();
</script>
@endpush
