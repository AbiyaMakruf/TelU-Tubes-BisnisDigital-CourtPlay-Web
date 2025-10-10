@extends('layouts.app-auth')

@section('title', 'Analytics')

@section('content')
<section class="dashboard py-5 d-flex align-items-center justify-content-center text-center min-vh-100">
    <div class="container">
        <h2 class="mb-3 fw-bold text-primary-500">Analytics</h2>
        <p class="text-white-400 fs-5">Belum ada hasil analisis video yang ditampilkan.</p>
        <a href="{{ route('videos.index') }}" class="btn btn-custom2 mt-3">Upload Video</a>
    </div>
</section>
@endsection
