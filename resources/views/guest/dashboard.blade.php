@extends('layouts.app')

@section('title', 'Home')

@section('content')
    @guest
        <section class="hero d-flex flex-column justify-content-center text-center position-relative">
            <div class="hero-light"></div> <!-- efek cahaya interaktif -->

            <div class="container position-relative z-2 py-5">
                <div class="row align-items-center justify-content-center flex-column-reverse flex-lg-row text-center text-lg-start">


                    <!-- Hero Text -->
                    <div class="col-lg-7 d-flex flex-column align-items-center align-items-lg-start justify-content-center">
                        <h1 class="mb-4 title-1">
                            Be Expert in Tennis <br>
                            and Padel <span>using AI</span>
                        </h1>
                        <a href="{{ route('signup') }}" class="btn btn-custom2 btn-lg">Get started for free</a>
                    </div>

                    <!-- Floating Logos -->
                    <div class="col-lg-5 mb-5 mb-lg-0 d-flex justify-content-center justify-content-lg-end">
                        <section class="floating-field">
                            <img src="/assets/Logo.svg" class="float-logo center" alt="Main Logo">
                            <img src="/assets/Logo.svg" class="float-logo small s1" alt="">
                            <img src="/assets/Logo.svg" class="float-logo small s2" alt="">
                            <img src="/assets/Logo.svg" class="float-logo small1 s3" alt="">
                            <img src="/assets/Logo.svg" class="float-logo small1 s4" alt="">
                            <img src="/assets/Logo.svg" class="float-logo small s5" alt="">
                            <img src="/assets/Logo.svg" class="float-logo small s6" alt="">
                        </section>
                    </div>


                </div>
            </div>

            <!-- Marquee -->
            <div class="logo-marquee mt-5 pb-3 w-80 mb-4">
                <div class="marquee-track d-flex align-items-center justify-content-center">
                    <img src="/assets/Gemini_logo.svg" alt="Gemini" class="mx-5" />
                    <img src="/assets/Ultralytics_logo.svg" alt="Ultralytics" class="mx-5" />
                    <img src="/assets/Google_Cloud_logo.svg" alt="Google Cloud" class="mx-5" />
                    <img src="/assets/Docker_logo.svg" alt="Docker" class="mx-5" />
                    <img src="/assets/Github_logo.svg" alt="GitHub" class="mx-5" />
                    <img src="/assets/Xendit_logo.svg" alt="Xendit" class="mx-5" />
                    <img src="/assets/Supabase_logo.svg" alt="Supabase" class="mx-5" />
                    <img src="/assets/Roboflow_logo.svg" alt="Roboflow" class="mx-5" />
                    <img src="/assets/Mlflow_logo.svg" alt="Mlflow" class="mx-5" />
                    <img src="/assets/Dagshub_logo.svg" alt="Dagshub" class="mx-5" />
                    <img src="/assets/Mailtrap_logo.svg" alt="Mailtrap" class="mx-5" />
                    <!-- Duplicate for seamless loop -->
                    <img src="/assets/Gemini_logo.svg" alt="Gemini" class="mx-5" />
                    <img src="/assets/Ultralytics_logo.svg" alt="Ultralytics" class="mx-5" />
                    <img src="/assets/Google_Cloud_logo.svg" alt="Google Cloud" class="mx-5" />
                    <img src="/assets/Docker_logo.svg" alt="Docker" class="mx-5" />
                    <img src="/assets/Github_logo.svg" alt="GitHub" class="mx-5" />
                    <img src="/assets/Xendit_logo.svg" alt="Xendit" class="mx-5" />
                    <img src="/assets/Supabase_logo.svg" alt="Supabase" class="mx-5" />
                    <img src="/assets/Roboflow_Cloud_logo.svg" alt="Roboflow Cloud" class="mx-5" />
                    <img src="/assets/Mlflow_logo.svg" alt="Mlflow" class="mx-5" />
                    <img src="/assets/Dagshub_logo.svg" alt="Dagshub" class="mx-5" />
                    <img src="/assets/Mailtrap_logo.svg" alt="Mailtrap" class="mx-5" />
                </div>
            </div>
        </section>

        <section class="container-section py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="text-center mt-5 mb-5">
                <h2 class="section-title mb-4 text-primary-300">
                Make Your Game Smarter with AI
                </h2>
                <p class="text-white-400 mb-5">
                From video upload to in-depth analytics and actionable advice — CourtPlay automates your tennis and padel insights.
                </p>

                </div>

            <!-- === LEFT: STEPS LIST === -->
                <div class="col-lg-6">
                    <div class="steps-wrapper position-relative">
                    <div class="vertical-line"></div>

                    <div class="step" data-step="1">
                        <div class="dot"></div>
                        <h4 class="text-primary-300">1. Upload Your Match</h4>
                        <p class="text-white-400">
                        Upload your tennis or padel match video directly. CourtPlay processes frames automatically.
                        </p>
                    </div>

                    <div class="step" data-step="2">
                        <div class="dot"></div>
                        <h4 class="text-primary-300">2. AI Analytics</h4>
                        <p class="text-white-400">
                        Detect every shot, track player movement, visualize player heatmaps, and ball heatmap instantly.
                        </p>
                    </div>

                    <div class="step" data-step="3">
                        <div class="dot"></div>
                        <h4 class="text-primary-300">3. AI Coaching Advice</h4>
                        <p class="text-white-400">
                        Get smart insights and personalized recommendations to improve your play.
                        </p>
                    </div>
                    </div>
                </div>

                <!-- === RIGHT: DYNAMIC IMAGE === -->
                <div class="col-lg-6 text-center mb-5">
                    <div class="image-preview position-relative">
                        <img src="/assets/Upload-AI.png" class="step-image active" data-step="1" alt="Upload Step">
                        <img src="/assets/Analytics-AI.png" class=" step-image" data-step="2" alt="Analytics Step">
                        <img src="/assets/Advice-AI.png" class="step-image" data-step="3" alt="Advice Step">
                    </div>
                </div>

            </div>
        </div>
        </section>

    @endguest

    @auth
        {{-- Tampilan untuk User Login --}}
        <section class="dashboard py-5 d-flex align-items-center justify-content-center text-center py-0">
            <div class="container">
                <h2 class="mb-3 fw-bold text-primary-500">Welcome back, {{ Auth::user()->firstname }} 👋</h2>
                <p class="text-white-400 fs-5">Belum ada hasil video yang dianalisis.</p>

                {{-- (opsional: tombol untuk upload video nantinya) --}}
                <button class="btn btn-custom2 mt-3" disabled>Upload Video (Coming Soon)</button>
            </div>
        </section>

    @endauth
@endsection

@push('styles')
<style>
@keyframes marquee {
  from { transform: translateX(0); }
  to { transform: translateX(-50%); } /* bergerak ke kiri tanpa henti */
}
</style>
@endpush
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {

    @if(session('toastr'))
        (function () {
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
        })();
    @endif

    @if(session('success')) toastr.success(@json(session('success'))); @endif
    @if(session('error'))   toastr.error(@json(session('error')));   @endif
    @if($errors->any())     toastr.error(@json($errors->first()));   @endif
});


</script>
@endpush


@push('scripts')

<script>
document.addEventListener('mousemove', (e) => {
  const hero = document.querySelector('.hero');
  const light = document.querySelector('.hero-light');
  if (!hero || !light) return;

  const rect = hero.getBoundingClientRect();
  const x = e.clientX - rect.left;
  const y = e.clientY - rect.top;

  light.style.background = `
    radial-gradient(
      100px circle at ${x}px ${y}px,
      rgba(var(--primary-300-rgb), 0.35),
      rgba(var(--primary-300-rgb), 0.05) 70%,
      transparent 100%
    )
  `;
});
</script>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
  let currentStep = 1;
  const steps = document.querySelectorAll('.step');
  const images = document.querySelectorAll('.step-image');
  const total = steps.length;

  function activateStep(n) {
    steps.forEach(s => s.classList.remove('active'));
    images.forEach(i => i.classList.remove('active'));

    const activeStep = document.querySelector(`.step[data-step="${n}"]`);
    const activeImg  = document.querySelector(`.step-image[data-step="${n}"]`);

    if (activeStep) activeStep.classList.add('active');
    if (activeImg) activeImg.classList.add('active');
  }

  activateStep(currentStep);

  setInterval(() => {
    currentStep = currentStep % total + 1; // loop 1 → 2 → 3 → 1
    activateStep(currentStep);
  }, 4000);
});
</script>

@endpush


