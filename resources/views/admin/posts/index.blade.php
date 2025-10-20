@extends('layouts.app-admin')
@section('title','Admin • News')
@section('page_title','News')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <form class="d-flex" method="get">
    <input name="q" class="form-control me-2" type="search" placeholder="Search title/slug…" value="{{ $q }}">
    <button class="btn btn-outline-secondary me-2">Search</button>
  </form>
  <a href="{{ route('admin.posts.create') }}" class="btn btn-custom"><i class="bi bi-plus-lg"></i> New Post</a>
</div>

<div class="table-responsive">
  <table class="table table-dark table-hover align-middle">
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
        <td class="fw-semibold">{{ $post->title }}</td>
        <td class="text-white-50">{{ $post->slug }}</td>
        <td>
          @if($post->is_published)
            <span class="badge bg-success">Published</span>
          @else
            <span class="badge bg-secondary">Draft</span>
          @endif
        </td>
        <td class="text-white-50">{{ $post->published_at?->format('Y-m-d H:i') ?: '—' }}</td>
        <td class="text-end">
          <form action="{{ route('admin.posts.toggle',$post) }}" method="POST" class="d-inline">
            @csrf @method('PATCH')
            <button class="btn btn-sm btn-primary-soft btn-outline-light">{{ $post->is_published ? 'Unpublish' : 'Publish' }}</button>
          </form>
          <a class="btn btn-sm btn-outline-warning" href="{{ route('admin.posts.edit',$post) }}"><i class="bi bi-pencil"></i> Edit</a>
          <form action="{{ route('admin.posts.destroy',$post) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this post?')">
            @csrf @method('DELETE')
            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i> Delete</button>
          </form>
        </td>
      </tr>
    @empty
      <tr><td colspan="5" class="text-center text-white-50">No posts</td></tr>
    @endforelse
    </tbody>
  </table>
</div>

<div class="mt-3">{{ $posts->links() }}</div>
@endsection
