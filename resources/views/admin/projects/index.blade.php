@extends('layouts.app-admin')
@section('title','Admin • Projects')
@section('page_title','Projects')

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
</style>
@endpush

@section('content')
<div class="admin-card p-4 mb-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <h5 class="mb-0 text-white">All Projects</h5>
        <form class="d-flex position-relative" method="get" action="{{ route('admin.projects.index') }}" style="min-width: 300px;">
            <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-white-50"></i>
            <input name="q" class="form-control form-control-modern ps-5" type="search" placeholder="Search project or username..." value="{{ $q ?? '' }}">
        </form>
    </div>
</div>

<div class="admin-card">
    <div class="table-responsive">
        <table class="table table-modern mb-0">
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
                        <div class="fw-semibold text-white">{{ $p->project_name }}</div>
                        <div class="text-white-50 small">ID: {{ $p->id }}</div>
                    </td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-dark d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                <span class="text-primary-300 fw-bold small">{{ substr(optional($p->user)->username ?? '?', 0, 1) }}</span>
                            </div>
                            <div>
                                <div class="fw-semibold text-white-300">{{ optional($p->user)->username ?? '—' }}</div>
                                <div class="text-white-50 small" style="font-size: 0.75rem;">{{ optional($p->user)->email ?? '' }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        @if($p->is_mailed)
                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3">Completed</span>
                        @else
                            <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-3">Pending</span>
                        @endif
                    </td>
                    <td class="text-white-50">
                        <i class="bi bi-calendar3 me-2"></i>{{ $p->upload_date?->format('d M Y, H:i') ?? $p->created_at?->format('d M Y, H:i') }}
                    </td>
                    <td class="text-end">
                        <form action="{{ route('admin.projects.destroy',$p) }}" method="POST"
                              onsubmit="return confirm('Delete this project and related data?')" class="d-inline">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger rounded-pill px-3">
                                <i class="bi bi-trash me-1"></i> Delete
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center text-white-50 py-5">No projects found</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

@if(method_exists($projects, 'links'))
  <div class="mt-4">{{ $projects->links() }}</div>
@endif
@endsection
