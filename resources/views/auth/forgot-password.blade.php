@extends('layouts.app')

@section('title', 'Forgot Password')

@section('content')
<div class="container py-5 d-flex justify-content-center">
    <div class="card p-4 shadow bg-black-200 text-white" style="max-width: 450px;">
        <h4 class="fw-bold text-primary-500 mb-3 text-center">Forgot Password</h4>
        <p class="text-white-400 text-center">Enter your email address to receive a reset link.</p>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">{{ $errors->first('email') }}</div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" class="form-control input-custom" name="email" required autofocus>
            </div>
            <button type="submit" class="btn btn-custom2 w-100">Send Reset Link</button>
        </form>

        <div class="text-center mt-3">
            <a href="{{ route('login') }}" class="text-primary-500">Back to login</a>
        </div>
    </div>
</div>
@endsection
