@extends('layouts.app')

@section('title', 'Home')

@section('content')
<section class="hero d-flex align-items-center justify-content-center text-center">
    <div class="container">
        <h1 class="mb-4 title-1">
            Be Expert in Tennis and <br>
            Padel <span>using AI</span>
        </h1>
        <a href="{{ route('signup') }}" class="btn btn-custom2 btn-lg">Create account</a>
    </div>
</section>
@endsection
