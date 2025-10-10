@extends(Auth::check() ? 'layouts.app-auth' : 'layouts.app')

@section('title', 'Home')

@section('content')
    @guest
        {{-- Tampilan untuk Guest --}}
        <section class="hero d-flex align-items-center justify-content-center text-center min-vh-100">
            <div class="container">
                <h1 class="mb-4 title-1">
                    Be Expert in Tennis and <br>
                    Padel <span>using AI</span>
                </h1>
                <a href="{{ route('signup') }}" class="btn btn-custom2 btn-lg">Create account</a>
            </div>
        </section>
    @endguest

    @auth
        {{-- Tampilan untuk User Login --}}
        <section class="dashboard py-5 d-flex align-items-center justify-content-center text-center min-vh-100">
            <div class="container">
                <h2 class="mb-3 fw-bold text-primary-500">Welcome back, {{ Auth::user()->firstname }} 👋</h2>
                <p class="text-white-400 fs-5">Belum ada hasil video yang dianalisis.</p>

                {{-- (opsional: tombol untuk upload video nantinya) --}}
                <button class="btn btn-custom2 mt-3" disabled>Upload Video (Coming Soon)</button>
            </div>
        </section>
    @endauth
@endsection
