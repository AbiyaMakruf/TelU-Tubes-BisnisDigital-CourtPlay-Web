@extends('layouts.app')

@section('title','Product & News')
@section('fullbleed', true)

@section('content')
<div class="container py-5 text-white">
  <div class="row">
    <div class="col-12 text-center mb-4">
      <h1 class="fw-bold text-primary-500">Product and News</h1>
    </div>

    {{-- FEATURED (jika ada) --}}
    @isset($featured)
    <div class="col-12 mb-4">
      <a href="{{ $featured->url }}" class="text-decoration-none">
        <div class="card bg-black-200 border-0 rounded-4 overflow-hidden">
          @if($featured->cover_url)
            <img src="{{ $featured->cover_url }}" alt="{{ $featured->title }}" class="w-100" style="height:360px; object-fit:cover;">
          @endif
        </div>
      </a>
    </div>
    <div class="col-12">
      <hr class="border-secondary opacity-25">
    </div>
    @endisset

    {{-- LIST --}}
    @forelse($posts as $post)
      <div class="col-12 mb-4">
        <div class="d-flex gap-3 align-items-start">
          {{-- Thumb kecil --}}
          <div class="flex-shrink-0">
            <div class="rounded-4 overflow-hidden" style="width:140px;height:100px;background:#262626;">
              @if($post->cover_url)
                <img src="{{ $post->cover_url }}" alt="{{ $post->title }}" style="width:100%;height:100%;object-fit:cover;">
              @endif
            </div>
          </div>

          {{-- Meta + judul + excerpt --}}
          <div class="flex-grow-1">
            <div class="text-white-400 small mb-1">
              {{ optional($post->published_at)->format('F d, Y') ?? '' }}
            </div>
            <a href="{{ $post->url }}" class="text-decoration-none">
              <h3 class="mb-2 text-primary-100 fw-bold">{{ $post->title }}</h3>
            </a>
            @if($post->excerpt)
              <p class="text-white-400 mb-2" style="max-width:62ch">{{ $post->excerpt }}</p>
            @endif
            <a href="{{ $post->url }}" class="btn btn-sm" style="background:#3a3a3a;color:#e6e6e6;border:0;border-radius:.5rem;padding:.5rem 1rem;">
              Read More
            </a>
          </div>
        </div>
      </div>

      <div class="col-12"><hr class="border-secondary opacity-25"></div>
    @empty
      <div class="col-12 text-center text-white-400 py-5">
        No news yet.
      </div>
    @endforelse

    <div class="col-12 d-flex justify-content-center mt-3">
      {{ $posts->links() }}
    </div>
  </div>
</div>
@endsection
