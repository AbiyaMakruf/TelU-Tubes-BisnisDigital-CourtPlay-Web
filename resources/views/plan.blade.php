@extends('layouts.app-auth')

@section('title', 'Upgrade Plan')

@section('content')
<section class="dashboard py-5 d-flex align-items-center justify-content-center text-center min-vh-100">
    <div class="container">
        <h2 class="mb-3 fw-bold text-primary-500">Your Plan</h2>
        <p class="text-white-400 fs-5">Anda saat ini menggunakan <strong>Basic Plan</strong>.</p>
        <p class="text-white-400 fs-5">Upgrade untuk membuka fitur analitik penuh dan AI auto-mapping!</p>
        <button class="btn btn-custom2 mt-3" disabled>Upgrade Plan (Coming Soon)</button>
    </div>
</section>
@endsection
