@extends('layouts.app')

@section('title', $post->title)
@section('fullbleed', true)

@section('content')
<div class="container py-5 text-white">
  <div class="row justify-content-center">
    <div class="col-lg-10">

      <a href="{{ route('news.index') }}" class="text-primary-500 text-decoration-none small d-inline-flex align-items-center mb-3">
        <i class="bi bi-arrow-left me-2"></i> Back to News
      </a>

      <h1 class="fw-bold text-primary-100">{{ $post->title }}</h1>
      <div class="text-white-400 small mb-3">
        {{ optional($post->published_at)->format('F d, Y') }}
      </div>

      @if($post->cover_url)
        <div class="rounded-4 overflow-hidden mb-4" style="background:#262626;">
          <img src="{{ $post->cover_url }}" alt="{{ $post->title }}" class="w-100" style="height:360px;object-fit:cover;">
        </div>
      @endif

      {{-- Konten --}}
      <article class="prose-dark">
        {!! $post->content !!}
      </article>

    </div>
  </div>
</div>

@push('styles')
<style>
/* Tipografi ringan untuk konten */
.prose-dark p{ color:#d6d6d6; line-height:1.75; margin-bottom:1rem; }
.prose-dark h2,.prose-dark h3{ color:#e9f0c0; margin:1.25rem 0 .75rem; font-weight:700; }
.prose-dark ul{ margin:0 0 1rem 1.25rem; }
.prose-dark li{ margin:.25rem 0; }
</style>
@endpush
@endsection
