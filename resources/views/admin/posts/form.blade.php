@extends('layouts.app-admin')
@section('title', 'Admin • ' . ($post->exists ? 'Edit Post' : 'New Post'))
@section('page_title', $post->exists ? 'Edit Post' : 'New Post')

@section('content')
<form method="POST" action="{{ $post->exists ? route('admin.posts.update',$post) : route('admin.posts.store') }}" enctype="multipart/form-data">
  @csrf
  @if($post->exists) @method('PUT') @endif

  <div class="row g-3">
    <div class="col-lg-8">
      <div class="card bg-transparent border-secondary">
        <div class="card-body">
          <div class="mb-3">
            <label class="form-label">Title</label>
            <input type="text" name="title" class="form-control" value="{{ old('title',$post->title) }}" required>
            @error('title') <div class="text-danger small">{{ $message }}</div> @enderror
          </div>
          <div class="mb-3">
            <label class="form-label">Slug (optional)</label>
            <input type="text" name="slug" class="form-control" value="{{ old('slug',$post->slug) }}">
            @error('slug') <div class="text-danger small">{{ $message }}</div> @enderror
          </div>
          <div class="mb-3">
            <label class="form-label">Excerpt (max 280)</label>
            <textarea name="excerpt" class="form-control" rows="2" maxlength="280">{{ old('excerpt',$post->excerpt) }}</textarea>
            @error('excerpt') <div class="text-danger small">{{ $message }}</div> @enderror
          </div>
          <div class="mb-3">
            <label class="form-label">Content</label>
            <textarea name="content" class="form-control" rows="10">{{ old('content',$post->content) }}</textarea>
            @error('content') <div class="text-danger small">{{ $message }}</div> @enderror
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-4">
      <div class="card bg-transparent border-secondary">
        <div class="card-body">
          <div class="mb-3">
            <label class="form-label">Cover (image)</label>
            <input type="file" name="cover" class="form-control" accept="image/*">
            @error('cover') <div class="text-danger small">{{ $message }}</div> @enderror
            @if($post->cover_url)
              <img src="{{ $post->cover_url }}" class="img-fluid rounded mt-2" alt="cover">
              <div class="small text-white-50 mt-1">Current cover</div>
            @endif
          </div>

          <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" id="is_published" name="is_published" value="1" {{ old('is_published',$post->is_published) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_published">Published</label>
          </div>

          <div class="mb-3">
            <label class="form-label">Published at</label>
            <input type="datetime-local" name="published_at" class="form-control"
                   value="{{ old('published_at', optional($post->published_at)->format('Y-m-d\TH:i')) }}">
          </div>

          <div class="d-flex gap-2">
            <button class="btn btn-custom">{{ $post->exists ? 'Update' : 'Create' }}</button>
            <a href="{{ route('admin.posts.index') }}" class="btn btn-outline-secondary">Back</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</form>
@endsection
