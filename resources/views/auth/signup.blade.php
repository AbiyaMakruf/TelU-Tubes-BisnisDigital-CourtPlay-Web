@extends('layouts.app')

@section('title', 'Sign Up')
@section('fullbleed', true)

@section('content')
<div class="container-fluid min-vh-100 d-flex align-items-center bg-black-300">
    <div class="container">
        <div class="row align-items-center">

            {{-- Kiri: Logo --}}
            <div class="col-lg-6 d-flex flex-column  text-white p-5">
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

            {{-- Kanan: Form --}}
            <div class="col-lg-6">
                <div class="card bg-transparent border-0 text-center">
                    {{-- Header --}}
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2 class="fw-bold text-primary-500 ">Sign up</h2>
                        <div>
                            <span class="text-primary-500 me-2">Already have account? <a href="{{ route('login')}}" class="text-primary-100">Sign in</a> </span>
                        </div>
                    </div>

                    {{-- Form --}}
                    <form method="POST" action="{{ route('signup') }}" class="text-start">
                        @csrf
                        <div class="mb-2">
                            <label for="firstname" class="form-label">First Name</label>
                            <input type="text" id="firstname" name="firstname" class="form-control input-custom" required autofocus>
                        </div>

                        <div class="mb-2">
                            <label for="lastname" class="form-label">Last Name</label>
                            <input type="text" id="lastname" name="lastname" class="form-control input-custom" required autofocus>
                        </div>

                        <div class="mb-2">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" id="email" name="email" class="form-control input-custom" required autofocus>
                        </div>


                        <div class="mb-2 position-relative">
                            <label for="password" class="form-label text-primary-500">Password</label>
                            <input type="password" id="password" name="password"
                                class="form-control bg-primary-500 text-black-300 pe-5 input-custom" required autofocus>

                            {{-- Ikon Mata di Dalam Input --}}
                            <i class="bi bi-eye toggle-password "
                            data-toggle="#password"></i>
                        </div>


                        <button type="submit" class="btn btn-custom2 w-100 mt-3">Sign in</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection



