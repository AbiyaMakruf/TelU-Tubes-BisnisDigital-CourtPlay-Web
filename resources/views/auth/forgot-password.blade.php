@extends('layouts.app')

@section('title', 'Forgot Password')
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
        <div class="row justify-content-center">
            <div class="col-lg-5">
                <div class="glass-card text-center">
                    <div class="mb-4">
                        <i class="bi bi-shield-lock fs-1 text-primary-300 mb-3 d-block"></i>
                        <h2 class="fw-bold text-white mb-2">Forgot Password?</h2>
                        <p class="text-white-400">
                            No worries! Enter your email address below and we'll send you a link to reset your password.
                        </p>
                    </div>

                    <form method="POST" action="{{ route('password.email') }}" class="text-start">
                        @csrf
                        <div class="mb-4">
                            <label for="email" class="form-label">Email Address</label>
                            <input
                                type="email"
                                id="email"
                                class="form-control"
                                name="email"
                                value="{{ old('email') }}"
                                placeholder="Enter your registered email"
                                required
                                autofocus
                            >
                        </div>
                        <button type="submit" class="btn btn-custom2 w-100 py-3 fw-bold mb-4">Send Reset Link</button>
                        
                        <div class="text-center">
                            <a href="{{ route('login') }}" class="auth-link">
                                <i class="bi bi-arrow-left me-1"></i> Back to Login
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
