@extends('layouts.app')

@section('title', $post->title)
@section('fullbleed', true)

@push('styles')
<style>
    .article-hero {
        position: relative;
        padding-top: 40px;
    }

    .article-hero::before {
        content: '';
        position: absolute;
        top: -20%;
        left: 50%;
        transform: translateX(-50%);
        width: 100%;
        height: 100%;
        background: radial-gradient(circle, rgba(var(--primary-300-rgb), 0.1) 0%, transparent 70%);
        z-index: -1;
    }

    .glass-card {
        background: rgba(255, 255, 255, 0.03);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 24px;
        padding: 3rem;
    }

    .prose-dark {
        color: var(--white-300);
        font-size: 1.15rem;
        line-height: 1.8;
    }
    .prose-dark p {
        margin-bottom: 1.8rem;
    }
    .prose-dark h2, .prose-dark h3 {
        color: white;
        font-weight: 700;
        margin-top: 3rem;
        margin-bottom: 1.5rem;
    }
    .prose-dark h2 { font-size: 2rem; }
    .prose-dark h3 { font-size: 1.5rem; }
    
    .prose-dark ul, .prose-dark ol {
        margin-bottom: 2rem;
        padding-left: 1.5rem;
    }
    .prose-dark li {
        margin-bottom: 0.8rem;
    }
    .prose-dark a {
        color: var(--primary-300);
        text-decoration: none;
        border-bottom: 1px solid rgba(var(--primary-300-rgb), 0.3);
        transition: all 0.2s;
    }
    .prose-dark a:hover {
        border-bottom-color: var(--primary-300);
        background: rgba(var(--primary-300-rgb), 0.1);
    }
    .prose-dark blockquote {
        border-left: 4px solid var(--primary-300);
        padding: 1.5rem 2rem;
        background: rgba(255,255,255,0.03);
        border-radius: 0 12px 12px 0;
        font-style: italic;
        color: var(--white-400);
        margin: 2.5rem 0;
    }
    .prose-dark img {
        max-width: 100%;
        border-radius: 16px;
        margin: 2.5rem 0;
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
    }

    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: var(--white-400);
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
        padding: 8px 16px;
        border-radius: 50px;
        background: rgba(255,255,255,0.05);
    }

    .back-link:hover {
        color: white;
        background: rgba(255,255,255,0.1);
        transform: translateX(-5px);
    }

    @media (max-width: 768px) {
        .glass-card {
            padding: 1.5rem;
        }
        .prose-dark {
            font-size: 1rem;
        }
    }
</style>
@endpush

@section('content')
<div class="article-hero container pb-5">
  <div class="row justify-content-center">
    <div class="col-lg-10 col-xl-8">

      <div class="mb-4">
          <a href="{{ route('news.index') }}" class="back-link">
            <i class="bi bi-arrow-left"></i> Back to News
          </a>
      </div>

      <div class="glass-card">
          <div class="mb-4">
            <span class="text-primary-300 fw-bold text-uppercase tracking-wider small">
                {{ optional($post->published_at)->format('F d, Y') }}
            </span>
          </div>

          <h1 class="fw-bold text-white display-5 mb-5">{{ $post->title }}</h1>

          @if($post->cover_url)
            <div class="rounded-4 overflow-hidden mb-5 shadow-lg position-relative">
              <img src="{{ $post->cover_url }}" alt="{{ $post->title }}" class="w-100" style="max-height:500px; object-fit:cover;">
            </div>
          @endif

          {{-- Konten --}}
          <article class="prose-dark">
            {!! $post->content !!}
          </article>
          
          <hr class="border-secondary my-5 opacity-25">
          
          <div class="d-flex justify-content-between align-items-center">
              <span class="text-white-50">Share this article:</span>
              <div class="d-flex gap-3">
                  <a href="#" class="text-white-400 hover-white"><i class="bi bi-twitter fs-5"></i></a>
                  <a href="#" class="text-white-400 hover-white"><i class="bi bi-facebook fs-5"></i></a>
                  <a href="#" class="text-white-400 hover-white"><i class="bi bi-linkedin fs-5"></i></a>
              </div>
          </div>
      </div>

    </div>
  </div>
</div>
@endsection
