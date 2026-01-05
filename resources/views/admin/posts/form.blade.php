@extends('layouts.app-admin')
@section('title', 'Admin • ' . ($post->exists ? 'Edit Post' : 'New Post'))
@section('page_title', $post->exists ? 'Edit Post' : 'New Post')

@push('styles')
<style>
    .admin-card {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 16px;
    }
    .form-control-modern {
        background: rgba(0, 0, 0, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: white;
        border-radius: 12px;
        padding: 0.8rem 1rem;
    }
    .form-control-modern:focus {
        background: rgba(0, 0, 0, 0.3);
        border-color: var(--primary-300);
        color: white;
        box-shadow: 0 0 0 0.25rem rgba(163, 206, 20, 0.15);
    }
    .form-label {
        color: #aaa;
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
    }
    .btn-custom {
        background: var(--primary-300);
        color: black;
        border: none;
        border-radius: 50px;
        padding: 0.6rem 1.5rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    .btn-custom:hover {
        background: #b4e61a;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(163, 206, 20, 0.3);
    }
</style>
@endpush

@section('content')
<form method="POST" action="{{ $post->exists ? route('admin.posts.update',$post) : route('admin.posts.store') }}" enctype="multipart/form-data">
  @csrf
  @if($post->exists) @method('PUT') @endif

  <div class="row g-4">
    <div class="col-lg-8">
      <div class="admin-card p-4">
          <div class="mb-4">
            <label class="form-label">Title</label>
            <div class="input-group">
                <input type="text" id="titleInput" name="title" class="form-control form-control-modern" value="{{ old('title',$post->title) }}" required placeholder="Enter post title">
                <button type="button" class="btn btn-custom d-flex align-items-center gap-2" id="btnGenerateAI">
                    <i class="bi bi-stars"></i> <span>Generate AI</span>
                </button>
            </div>
            @error('title') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
          </div>
          <div class="mb-4">
            <label class="form-label">Slug (optional)</label>
            <input type="text" name="slug" class="form-control form-control-modern" value="{{ old('slug',$post->slug) }}" placeholder="custom-url-slug">
            @error('slug') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
          </div>
          <div class="mb-4">
            <label class="form-label">Excerpt (max 280)</label>
            <textarea name="excerpt" class="form-control form-control-modern" rows="3" maxlength="280" placeholder="Short summary...">{{ old('excerpt',$post->excerpt) }}</textarea>
            @error('excerpt') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
          </div>
          <div class="mb-4">
            <label class="form-label">Content</label>
            <textarea name="content" class="form-control form-control-modern" rows="12" placeholder="Write your content here...">{{ old('content',$post->content) }}</textarea>
            @error('content') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
          </div>
      </div>
    </div>

    <div class="col-lg-4">
      <div class="admin-card p-4">
          <div class="mb-4">
            <label class="form-label">Cover Image</label>
            <input type="file" name="cover" class="form-control form-control-modern" accept="image/*">
            @error('cover') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
            @if($post->cover_url)
              <div class="mt-3 position-relative">
                  <img src="{{ $post->cover_url }}" class="img-fluid rounded-3 border border-secondary" alt="cover">
                  <div class="position-absolute top-0 end-0 m-2 badge bg-black bg-opacity-75">Current</div>
              </div>
            @endif
          </div>

          <div class="mb-4">
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" role="switch" id="is_published" name="is_published" value="1" {{ old('is_published',$post->is_published) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_published">Published</label>
            </div>
          </div>

          <div class="mb-4">
            <label class="form-label">Published Date</label>
            <input type="datetime-local" name="published_at" class="form-control form-control-modern"
                   value="{{ old('published_at', optional($post->published_at)->format('Y-m-d\TH:i')) }}">
          </div>

          <div class="d-grid gap-2">
            <button class="btn btn-custom">{{ $post->exists ? 'Update Post' : 'Create Post' }}</button>
            <a href="{{ route('admin.posts.index') }}" class="btn btn-outline-light rounded-pill">Cancel</a>
          </div>
      </div>
    </div>
  </div>
</form>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const btnGenerateAI = document.getElementById('btnGenerateAI');
    const titleInput = document.getElementById('titleInput');
    const excerptInput = document.querySelector('textarea[name="excerpt"]');
    const contentInput = document.querySelector('textarea[name="content"]');

    if (btnGenerateAI) {
        btnGenerateAI.addEventListener('click', async function() {
            const title = titleInput.value;
            if (!title) {
                if(window.toastr) toastr.warning('Please enter a title first.');
                else alert('Please enter a title first.');
                titleInput.focus();
                return;
            }

            const btn = this;
            const originalContent = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Generating...';

            try {
                const response = await fetch('{{ route('admin.posts.generate-ai') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ title: title })
                });

                const result = await response.json();

                if (response.ok && result.success) {
                    if (excerptInput) excerptInput.value = result.data.excerpt || '';
                    if (contentInput) contentInput.value = result.data.content || '';
                    
                    if(result.data.image_prompt) {
                        console.log('Suggested Image Prompt:', result.data.image_prompt);
                        if(window.toastr) toastr.info('AI Suggested Image Prompt: ' + result.data.image_prompt);
                    }
                    
                    if(window.toastr) toastr.success('Content generated successfully!');
                } else {
                    const msg = result.error || 'Failed to generate content.';
                    if(window.toastr) toastr.error(msg);
                    else alert(msg);
                }
            } catch (error) {
                console.error(error);
                if(window.toastr) toastr.error('An error occurred while connecting to AI.');
                else alert('An error occurred.');
            } finally {
                btn.disabled = false;
                btn.innerHTML = originalContent;
            }
        });
    }
});
</script>
@endpush
