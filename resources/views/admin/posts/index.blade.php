@extends('layouts.app-admin')
@section('title','Admin • News')
@section('page_title','News')

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
        border-radius: 50px;
        padding: 0.6rem 1.2rem;
    }
    .form-control-modern:focus {
        background: rgba(0, 0, 0, 0.3);
        border-color: var(--primary-300);
        color: white;
        box-shadow: 0 0 0 0.25rem rgba(163, 206, 20, 0.15);
    }
    .table-modern th {
        background: transparent;
        color: #888;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        padding: 1rem;
    }
    .table-modern td {
        background: transparent;
        color: white;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        padding: 1rem;
        vertical-align: middle;
    }
    .table-modern tr:last-child td {
        border-bottom: none;
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
<div class="admin-card p-4 mb-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <form class="d-flex position-relative" method="get" style="min-width: 300px;">
            <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-white-50"></i>
            <input name="q" class="form-control form-control-modern ps-5" type="search" placeholder="Search title/slug..." value="{{ $q }}">
        </form>
        <a href="{{ route('admin.posts.create') }}" class="btn btn-custom">
            <i class="bi bi-plus-lg me-1"></i> New Post
        </a>
    </div>
</div>

<div class="admin-card">
    <div class="table-responsive">
        <table class="table table-modern mb-0">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Slug</th>
                    <th>Status</th>
                    <th>Published</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($posts as $post)
                <tr>
                    <td class="fw-semibold text-white">{{ $post->title }}</td>
                    <td class="text-white-50">{{ $post->slug }}</td>
                    <td>
                        @if($post->is_published)
                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3">Published</span>
                        @else
                            <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-3">Draft</span>
                        @endif
                    </td>
                    <td class="text-white-50">
                        <i class="bi bi-calendar3 me-2"></i>{{ $post->published_at?->format('d M Y, H:i') ?: '—' }}
                    </td>
                    <td class="text-end">
                        <div class="d-flex justify-content-end gap-2">
                            <form action="{{ route('admin.posts.toggle',$post) }}" method="POST" class="d-inline">
                                @csrf @method('PATCH')
                                <button class="btn btn-sm {{ $post->is_published ? 'btn-outline-warning' : 'btn-outline-success' }} rounded-pill px-3">
                                    {{ $post->is_published ? 'Unpublish' : 'Publish' }}
                                </button>
                            </form>
                            <a class="btn btn-sm btn-outline-info rounded-pill px-3" href="{{ route('admin.posts.edit',$post) }}">
                                <i class="bi bi-pencil me-1"></i> Edit
                            </a>
                            <form action="{{ route('admin.posts.destroy',$post) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this post?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger rounded-pill px-3">
                                    <i class="bi bi-trash me-1"></i> Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center text-white-50 py-5">No posts found</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">{{ $posts->links() }}</div>
@endsection
