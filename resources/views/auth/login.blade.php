@extends('layouts.app')

@section('title', 'Login')
@section('fullbleed', true)

@section('content')
<div class="login-page d-flex min-vh-100 align-items-center">
    <div class="container">
        <div class="row align-items-center">

            {{-- Kiri: Logo --}}
            <div class="col-lg-6 text-center justify-content-center mb-5 mb-lg-0">
                <img src="{{ asset('assets/Logo Vertical.svg') }}" alt="Court Play" class="mb-4" style="width: 300px;">
            </div>

            {{-- Kanan: Form --}}
            <div class="col-lg-6">
                <div class="card bg-transparent border-0 text-center">
                    {{-- Header --}}
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2 class="fw-bold text-primary-500">Sign in</h2>
                        <div>
                            <span class="text-primary-500 me-2">
                                New to Court Play?
                                <a href="{{ route('signup') }}" class="text-primary-100">Sign up</a>
                            </span>
                        </div>
                    </div>

                    {{-- Form --}}
                    <form method="POST" action="{{ route('login.post') }}" class="text-start">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label">Email or Username</label>
                            <input type="text" id="email" name="email" class="form-control input-custom" required autofocus>
                        </div>

                        <div class="mb-3 position-relative">
                            <label for="password" class="form-label text-primary-500">Password</label>
                            <input type="password" id="password" name="password"
                                   class="form-control bg-primary-500 text-black-300 pe-5 input-custom" required>

                            {{-- Ikon Mata di Dalam Input --}}
                            <i class="bi bi-eye toggle-password" data-toggle="#password"></i>
                        </div>

                        {{-- Remember Me --}}
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember">
                            <label class="form-check-label text-white-400" for="remember">
                                Remember me
                            </label>
                        </div>

                        <button type="submit" class="btn btn-custom2 w-100 mt-2">Sign in</button>

                        {{-- Link Forgot Password --}}
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
