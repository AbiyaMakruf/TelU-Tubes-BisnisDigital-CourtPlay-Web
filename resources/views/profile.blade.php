@extends('layouts.app-auth')

@section('title', 'Profile')

@section('content')
<section class="dashboard py-5 d-flex align-items-center justify-content-center text-center min-vh-100">
    <div class="container">
        <h2 class="mb-3 fw-bold text-primary-500">Profile</h2>
        <p class="text-white-400 fs-5">Edit informasi akun Anda akan tersedia di sini.</p>

        <div class="card bg-transparent border rounded-3 mt-4 mx-auto" style="max-width: 500px;">
            <div class="card-body text-start text-white-400">
                <p><strong>Name:</strong> {{ Auth::user()->firstname }} {{ Auth::user()->lastname ?? '' }}</p>
                <p><strong>Email:</strong> {{ Auth::user()->email }}</p>
                <p><strong>Joined:</strong> {{ Auth::user()->created_at->format('d M Y') }}</p>
            </div>
        </div>

        <button class="btn btn-custom2 mt-4" disabled>Edit Profile (Coming Soon)</button>
    </div>
</section>
@endsection
