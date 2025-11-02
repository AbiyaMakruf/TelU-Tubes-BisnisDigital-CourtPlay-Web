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
                <p class="text-primary-500 mb-5">
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
                        <p class="text-primary-500">
                        Upload your tennis or padel match video directly. CourtPlay processes frames automatically.
                        </p>
                    </div>

                    <div class="step" data-step="2">
                        <div class="dot"></div>
                        <h4 class="text-primary-300">2. AI Analytics</h4>
                        <p class="text-primary-500">
                        Detect every shot, track player movement, visualize player heatmaps, and ball heatmap instantly.
                        </p>
                    </div>

                    <div class="step" data-step="3">
                        <div class="dot"></div>
                        <h4 class="text-primary-300">3. AI Coaching Advice</h4>
                        <p class="text-primary-500">
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

    <section class="demo-section py-5">
    <div class="container text-center">
        <h2 class="section-title mb-3 text-primary-300">See CourtPlay in Action</h2>
        <p class="text-primary-500 mb-5">
        From raw match video to actionable performance analytics — all in under 5 minutes.
        </p>

        <div class="demo-video-wrapper mx-auto shadow-lg rounded-4 overflow-hidden border border-primary-500 mb-5">
        <video autoplay muted loop playsinline>
            <source src="https://storage.googleapis.com/courtplay-storage/assets/Web/landing-page%20vid.mp4" type="video/mp4">
        </video>
        </div>
    </div>
    </section>

    <section class="usecase-section py-5">
    <div class="container text-center">
        <h2 class="section-title mb-3 text-primary-300">Built for Players, Coaches, and Clubs</h2>
        <p class="text-primary-500 mb-5">
        CourtPlay adapts to your workflow — from solo training sessions to professional team management.
        </p>

        <div class="row justify-content-center g-4">
        <div class="col-md-4">
            <div class="usecase-card p-4 border border-dark rounded-4 h-100">
            <i class="bi bi-person-badge fs-2 text-primary-300 mb-3"></i>
            <h5 class="text-primary-500 ">For Players</h5>
            <p class="text-primary-500">Get personalized AI insights on your technique, movement, and decision-making.</p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="usecase-card p-4 border border-dark rounded-4 h-100">
            <i class="bi bi-graph-up fs-2 text-primary-300 mb-3"></i>
            <h5 class="text-primary-500 ">For Coaches</h5>
            <p class="text-primary-500 ">Track athlete performance, analyze trends, and optimize training plans using data.</p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="usecase-card p-4 border border-dark rounded-4 h-100">
            <i class="bi bi-building fs-2 text-primary-300 mb-3"></i>
            <h5 class="text-primary-500 ">For Clubs</h5>
            <p class="text-primary-500 ">Centralize team stats, compare players, and elevate your club’s performance analytics.</p>
            </div>
        </div>
        </div>
    </div>
    </section>




    <section class="testimonials-section py-5">
        <div class="container py-5">
            <div class="text-center mb-5">
                <h2 class="section-title mb-3 text-primary-500 ">
                    Trusted by Players and Coaches Worldwide
                </h2>
                <p class="text-primary-500  fs-5">
                    Hear how CourtPlay is transforming training sessions and match results.
                </p>
            </div>

            <div class="row justify-content-center">

                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card testimonial-card h-100 p-4 border-0 shadow-lg" >
                        <div class="card-body">
                            <p class="card-text fs-6 fst-italic text-white-400">
                                <i class="fas fa-quote-left text-primary-300 me-2"></i>
                                "CourtPlay has revolutionized my coaching. I now have
                                <strong>objective data</strong>
                                to show my athletes, cutting correction time from weeks to just minutes.
                                The heatmap analysis is a <strong>game-changer</strong>."
                                <i class="fas fa-quote-right text-primary-300 ms-2"></i>
                            </p>
                        </div>
                        <div class="d-flex align-items-center mt-3">
                            <i class="bi bi-person-circle fs-2 text-primary-300 me-3"></i>
                            <div>
                                <h5 class="mb-0 text-primary-300">Coach David R.</h5>
                                <p class="text-primary-500  small">Certified Tennis Instructor</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card testimonial-card h-100 p-4 border-0 shadow-lg">
                        <div class="card-body">
                            <p class="card-text fs-6 fst-italic text-white-400">
                                <i class="fas fa-quote-left text-primary-300 me-2"></i>
                                "I always thought my forehand was my strength, but CourtPlay instantly showed me where I was making
                                <strong>unforced errors under pressure</strong>.
                                The personalized AI advice helped me fix it fast!"
                                <i class="fas fa-quote-right text-primary-300 ms-2"></i>
                            </p>
                        </div>
                        <div class="d-flex align-items-center mt-3">
                            <i class="bi bi-person-circle fs-2 text-primary-300 me-3"></i>
                            <div>
                                <h5 class="mb-0 text-primary-300">Sarah K.</h5>
                                <p class="text-primary-500  small">Competitive Padel Player</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card testimonial-card h-100 p-4 border-0 shadow-lg" >
                        <div class="card-body">
                            <p class="card-text fs-6 fst-italic text-white-400">
                                <i class="fas fa-quote-left text-primary-300 me-2"></i>
                                "The ability to track player movement and fatigue over multiple matches is invaluable. It’s like having an
                                <strong>entire analytics team</strong>
                                for a fraction of the cost. Highly recommended for any club."
                                <i class="fas fa-quote-right text-primary-300 ms-2"></i>
                            </p>
                        </div>
                        <div class="d-flex align-items-center mt-3">
                            <i class="bi bi-person-circle fs-2 text-primary-300 me-3"></i>
                            <div>
                                <h5 class="mb-0 text-primary-300">Michael L.</h5>
                                <p class="text-primary-500 small">Sports Club Manager</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </section>

    <section class="faq-section py-5">
            <div class="container py-5">
                <div class="text-center mb-5">
                    <h2 class="section-title mb-3 ">
                    Frequently Asked Questions (FAQ)
                    </h2>
                    <p class="-400 fs-5">
                        Find quick answers to common questions about CourtPlay.
                    </p>
                </div>
            <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="accordion accordion-flush" id="faqAccordion">

                    {{-- Q1: Hardware Requirements --}}
                    <div class="accordion-item" >
                        <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button collapsed " type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne" style="background-color: transparent;">
                                Do I need special cameras or sensors to use CourtPlay?
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#faqAccordion">
                            <div class="accordion-body ">
                                **Not at all!** One of CourtPlay's main advantages is its ability to analyze video recorded with standard devices, such as a smartphone (Android/iOS) or a regular digital camera. Just ensure your footage has decent resolution and the players and court are clearly visible.
                            </div>
                        </div>
                    </div>

                    {{-- Q2: Processing Time --}}
                    <div class="accordion-item" >
                        <h2 class="accordion-header" id="headingTwo">
                            <button class="accordion-button collapsed " type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo" style="background-color: transparent;">
                                How long does it take to analyze one match?
                            </button>
                        </h2>
                        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#faqAccordion">
                            <div class="accordion-body ">
                                Processing time is very fast, thanks to cloud infrastructure optimization. On average, a **60-minute** match video will be fully analyzed and reported within **5 to 15 minutes**. You will receive a notification once your report is ready.
                            </div>
                        </div>
                    </div>

                    {{-- Q3: Data Security --}}
                    <div class="accordion-item" >
                        <h2 class="accordion-header" id="headingThree">
                            <button class="accordion-button collapsed " type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree" style="background-color: transparent;">
                                Are my match data and personal information secure?
                            </button>
                        </h2>
                        <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#faqAccordion">
                            <div class="accordion-body ">
                                Yes. We treat data privacy seriously. All uploaded videos are encrypted, and access to your analytics data is solely yours. We use leading cloud services (Google Cloud/Supabase) that comply with global data security standards.
                            </div>
                        </div>
                    </div>

                    {{-- Q4: Other Sports Support --}}
                    <div class="accordion-item" >
                        <h2 class="accordion-header" id="headingFour">
                            <button class="accordion-button collapsed " type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour" style="background-color: transparent;">
                                Does CourtPlay support sports other than Tennis and Padel?
                            </button>
                        </h2>
                        <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#faqAccordion">
                            <div class="accordion-body ">
                                Currently, our main focus is on **Tennis and Padel**, where our AI delivers the most accurate results. However, we are developing and planning to expand the analysis scope to other racquet sports (like Badminton and Squash) in the future.
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            </div>
            </div>
        </section>

        <footer class="footer-section py-5 mt-5 border-top border-dark bg-black text-light">
        <div class="container">
            <div class="row gy-4 align-items-start justify-content-between">

                <!-- LEFT: Logo & Copyright -->
                <div class="col-md-4 d-flex flex-column">
                    <div class="d-flex align-items-center mb-3">
                        <img src="/assets/Logo.svg" alt="CourtPlay Logo" class="me-2" style="height: 32px;">
                        <h5 class="m-0 fw-bold text-primary-300">CourtPlay</h5>
                    </div>
                    <small class="text-primary-500">&copy; 2025 CourtPlay. All rights reserved.</small>
                </div>

                <!-- MIDDLE: Navigation Links -->
                <div class="col-md-4 d-flex justify-content-center">
                    <div class="row w-100 text-center text-md-start ">
                        <div class="col-6">
                            <ul class="list-unstyled">
                                <li><a href="/features" class="footer-link text-primary-500">Features</a></li>
                                <li><a href="/pricing" class="footer-link text-primary-500">Pricing</a></li>
                                <li><a href="/blog" class="footer-link text-primary-500">Blog</a></li>
                            </ul>
                        </div>
                        <div class="col-6 ">
                            <ul class="list-unstyled">
                                <li><a href="/about" class="footer-link text-primary-500">About Us</a></li>
                                <li><a href="/contact" class="footer-link text-primary-500">Contact</a></li>
                                <li><a href="/privacy" class="footer-link text-primary-500">Privacy Policy</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- RIGHT: Social Icons & Address -->
                <div class="col-md-4 d-flex flex-column align-items-md-end align-items-center text-md-end text-center">
                    <div class="d-flex gap-3 mb-3 fs-5 text-primary-500">
                        <a href="https://www.instagram.com/courtplay" class="footer-icon text-primary-500"><i class="bi bi-instagram"></i></a>
                        <a href="https://x.com/courtplay" class="footer-icon text-primary-500"><i class="bi bi-twitter-x"></i></a>
                        <a href="https://www.tiktok.com/@courtplay" class="footer-icon text-primary-500"><i class="bi bi-tiktok"></i></a>
                    </div>
                    <small class="text-primary-500">
                        Bandung, Indonesia<br>
                        <span class="text-primary-300">contact@courtplay.my.id</span>
                    </small>
                </div>

            </div>
        </div>
    </footer>



    @endguest

    @auth
        {{-- Tampilan untuk User Login --}}
        <section class="dashboard py-5 d-flex align-items-center justify-content-center text-center py-0">
            <div class="container">
                <h2 class="mb-3 fw-bold text-primary-500">Welcome back, {{ Auth::user()->firstname }} 👋</h2>
                <p class="text-primary-500 fs-5">Belum ada hasil video yang dianalisis.</p>

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
.navbar-expand-lg.sticky-top {
    /* Ini akan memastikan navbar berada di atas konten lain (modal menggunakan 1050) */
    z-index: 1030;
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


