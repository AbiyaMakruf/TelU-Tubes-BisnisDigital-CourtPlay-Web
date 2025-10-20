@extends('layouts.app-admin')
@section('title','Admin • Projects')
@section('page_title','Projects')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h5 class="mb-0">All Projects</h5>
</div>

<div class="d-flex justify-content-between align-items-center mb-3">
  <form class="d-flex" method="get" action="{{ route('admin.projects.index') }}">
    <input name="q" class="form-control me-2" type="search"
           placeholder="Search project or username…" value="{{ $q ?? '' }}">
    <button class="btn btn-outline-secondary sm" type="submit"> Search
    </button>
  </form>
</div>

<div class="table-responsive">
  <table class="table table-dark table-hover align-middle">
    <thead>
      <tr>
        <th>Project</th>
        <th>User</th>
        <th>Status</th>
        <th>Uploaded</th>
        <th class="text-end">Actions</th>
      </tr>
    </thead>
    <tbody>
    @forelse($projects as $p)
      <tr>
        <td>
          <div class="fw-semibold">{{ $p->project_name }}</div>
          <div class="text-white-50 small">ID: {{ $p->id }}</div>
        </td>
        <td>
          <div class="fw-semibold">{{ optional($p->user)->username ?? '—' }}</div>
          <div class="text-white-50 small">{{ optional($p->user)->email ?? '' }}</div>
        </td>
        <td>
          @if($p->is_mailed)
            <span class="badge bg-success">Completed</span>
          @else
            <span class="badge bg-secondary">Pending</span>
          @endif
        </td>
        <td class="text-white-50">
          {{ $p->upload_date?->format('Y-m-d H:i') ?? $p->created_at?->format('Y-m-d H:i') }}
        </td>
        <td class="text-end">
          <form action="{{ route('admin.projects.destroy',$p) }}" method="POST"
                onsubmit="return confirm('Delete this project and related data?')" class="d-inline">
            @csrf @method('DELETE')
            <button class="btn btn-sm btn-outline-danger">
              <i class="bi bi-trash"></i> Delete
            </button>
          </form>
        </td>
      </tr>
    @empty
      <tr><td colspan="5" class="text-center text-white-50">No projects</td></tr>
    @endforelse
    </tbody>
  </table>
</div>

@if(method_exists($projects, 'links'))
  <div class="mt-3">{{ $projects->links() }}</div>
@endif
@endsection
