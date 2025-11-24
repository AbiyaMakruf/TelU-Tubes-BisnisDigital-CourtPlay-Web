@extends('layouts.app')

@section('title','Product & News')
@section('fullbleed', true)

@push('styles')
<style>
    .news-hero {
        position: relative;
        padding: 80px 0 40px;
        overflow: hidden;
    }
    
    .news-hero::before {
        content: '';
        position: absolute;
        top: -50%;
        left: 50%;
        transform: translateX(-50%);
        width: 100%;
        height: 200%;
        background: radial-gradient(circle, rgba(var(--primary-300-rgb), 0.15) 0%, transparent 70%);
        z-index: -1;
    }

    .glass-card {
        background: rgba(255, 255, 255, 0.03);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 24px;
        transition: all 0.3s ease;
        overflow: hidden;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .glass-card:hover {
        transform: translateY(-5px);
        border-color: rgba(var(--primary-300-rgb), 0.3);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }

    .news-thumb {
        height: 240px;
        width: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .glass-card:hover .news-thumb {
        transform: scale(1.05);
    }

    .featured-card {
        position: relative;
        border-radius: 32px;
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .featured-thumb {
        height: 500px;
        width: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .featured-card:hover .featured-thumb {
        transform: scale(1.03);
    }

    .featured-overlay {
        background: linear-gradient(to top, rgba(0,0,0,0.95) 0%, rgba(0,0,0,0.6) 50%, transparent 100%);
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        padding: 3rem;
    }

    .category-badge {
        background: rgba(var(--primary-300-rgb), 0.2);
        color: var(--primary-300);
        border: 1px solid rgba(var(--primary-300-rgb), 0.3);
        padding: 6px 16px;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        display: inline-block;
        margin-bottom: 1rem;
    }

    .read-more-link {
        color: var(--primary-300);
        text-decoration: none;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: gap 0.3s ease;
    }

    .read-more-link:hover {
        color: #b4e61a;
        gap: 12px;
    }

    @media (max-width: 768px) {
        .featured-thumb {
            height: 400px;
        }
        .featured-overlay {
            padding: 1.5rem;
        }
        .news-hero {
            padding: 60px 0 20px;
        }
    }
</style>
@endpush

@section('content')
<section class="news-hero">
    <div class="container text-center">
        <h1 class="display-4 fw-bold text-white mb-3">
            News & <span class="text-primary-300">Updates</span>
        </h1>
        <p class="lead text-white-300 mx-auto" style="max-width: 600px;">
            Stay ahead of the game with the latest features, announcements, and insights from the CourtPlay team.
        </p>
    </div>
</section>

<div class="container pb-5">
    {{-- FEATURED POST --}}
    @isset($featured)
    <div class="row mb-5">
        <div class="col-12">
            <a href="{{ $featured->url }}" class="text-decoration-none">
                <div class="featured-card group">
                    @if($featured->cover_url)
                        <img src="{{ $featured->cover_url }}" alt="{{ $featured->title }}" class="featured-thumb">
                    @endif
                    <div class="featured-overlay">
                        <span class="category-badge">Featured</span>
                        <h2 class="display-6 fw-bold text-white mb-3">{{ $featured->title }}</h2>
                        <p class="text-white-300 mb-0 d-none d-md-block fs-5" style="max-width: 800px;">
                            {{ Str::limit($featured->excerpt, 200) }}
                        </p>
                    </div>
                </div>
            </a>
        </div>
    </div>
    @endisset

    {{-- POSTS GRID --}}
    <div class="row g-4">
        @forelse($posts as $post)
        <div class="col-md-6 col-lg-4">
            <div class="glass-card">
                <a href="{{ $post->url }}" class="text-decoration-none overflow-hidden position-relative">
                    @if($post->cover_url)
                        <img src="{{ $post->cover_url }}" alt="{{ $post->title }}" class="news-thumb">
                    @else
                        <div class="news-thumb bg-dark d-flex align-items-center justify-content-center">
                            <i class="bi bi-image text-white-50 fs-1"></i>
                        </div>
                    @endif
                </a>

                <div class="p-4 d-flex flex-column flex-grow-1">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <span class="text-primary-300 small fw-bold text-uppercase tracking-wider">
                            {{ optional($post->published_at)->format('M d, Y') ?? 'Draft' }}
                        </span>
                    </div>

                    <a href="{{ $post->url }}" class="text-decoration-none">
                        <h4 class="text-white fw-bold mb-3 lh-sm">{{ $post->title }}</h4>
                    </a>
                    
                    <p class="text-white-400 small mb-4 flex-grow-1">
                        {{ Str::limit($post->excerpt, 120) }}
                    </p>

                    <div class="mt-auto">
                        <a href="{{ $post->url }}" class="read-more-link">
                            Read Article <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <div class="glass-card p-5 d-inline-block">
                <i class="bi bi-newspaper fs-1 text-white-50 mb-3"></i>
                <h3 class="text-white">No updates yet</h3>
                <p class="text-white-50">Check back soon for the latest news.</p>
            </div>
        </div>
        @endforelse
    </div>

    <div class="mt-5 d-flex justify-content-center">
        {{ $posts->links() }}
    </div>
</div>
@endsection
