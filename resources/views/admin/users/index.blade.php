@extends('layouts.app-admin')
@section('title','Admin • Users')
@section('page_title','Users')

@section('content')
<div class="admin-card p-4 mb-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <form class="d-flex position-relative" method="get" style="min-width: 300px;">
            <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-white-50"></i>
            <input name="q" class="form-control form-control-modern ps-5" type="search" placeholder="Search users..." value="{{ $q }}">
        </form>

        <div class="d-flex flex-wrap gap-2">
            <div class="role-badge bg-primary-300 text-black">
                <i class="bi bi-person-fill"></i> Free: {{ $roleCounts['free'] ?? 0 }}
            </div>
            <div class="role-badge bg-info text-black">
                <i class="bi bi-star-fill"></i> Pro: {{ $roleCounts['pro'] ?? 0 }}
            </div>
            <div class="role-badge bg-warning text-black">
                <i class="bi bi-lightning-fill"></i> Plus: {{ $roleCounts['plus'] ?? 0 }}
            </div>
            <div class="role-badge bg-danger text-white">
                <i class="bi bi-shield-lock-fill"></i> Admin: {{ $roleCounts['admin'] ?? 0 }}
            </div>
        </div>
    </div>
</div>

<div class="admin-card">
    <div class="table-responsive">
        <table class="table table-modern mb-0">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Username / Email</th>
                    <th style="width:200px">Role</th>
                    <th>Joined</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($users as $u)
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-dark d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                <span class="text-primary-300 fw-bold fs-5">{{ substr($u->first_name, 0, 1) }}</span>
                            </div>
                            <span class="fw-medium text-white">{{ trim(($u->first_name ?? '').' '.($u->last_name ?? '')) ?: '—' }}</span>
                        </div>
                    </td>
                    <td>
                        <div class="fw-semibold text-white-300">{{ $u->username }}</div>
                        <div class="text-white-50 small">{{ $u->email }}</div>
                    </td>
                    <td>
                        <form action="{{ route('admin.users.role',$u) }}" method="POST" class="m-0 p-0 role-form">
                            @csrf @method('PATCH')
                            <select name="role" class="form-select form-select-modern form-select-sm" onchange="this.form.submit()">
                                @php $roles=['free','pro','plus','admin']; @endphp
                                @foreach($roles as $r)
                                    <option value="{{ $r }}" {{ strtolower($u->role ?? 'free')===$r?'selected':'' }}>{{ ucfirst($r) }}</option>
                                @endforeach
                            </select>
                        </form>
                    </td>
                    <td class="text-white-50">
                        <i class="bi bi-calendar3 me-2"></i>{{ $u->created_at?->format('d M Y') }}
                    </td>
                    <td class="text-end">
                        <form action="{{ route('admin.users.destroy',$u) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this user and ALL related data?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger rounded-pill px-3">
                                <i class="bi bi-trash me-1"></i> Delete
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center text-white-50 py-5">No users found</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">{{ $users->links() }}</div>
@endsection

@push('styles')
<style>
    /* Modern Admin Users Styles */
    .admin-card {
        background: rgba(255, 255, 255, 0.03);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 16px;
        overflow: hidden;
    }

    .table-modern {
        --bs-table-bg: transparent;
        --bs-table-color: var(--white-400);
        --bs-table-border-color: rgba(255, 255, 255, 0.05);
    }

    .table-modern th {
        font-weight: 600;
        color: var(--white-300);
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
        padding: 1rem 1.5rem;
        background: rgba(255, 255, 255, 0.02);
    }

    .table-modern td {
        padding: 1rem 1.5rem;
        vertical-align: middle;
    }

    .form-control-modern, .form-select-modern {
        background: rgba(0, 0, 0, 0.3);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: var(--white-500);
        border-radius: 50px;
        padding: 0.6rem 1.2rem;
    }

    .form-control-modern:focus, .form-select-modern:focus {
        background: rgba(0, 0, 0, 0.5);
        border-color: var(--primary-300);
        box-shadow: 0 0 0 4px rgba(var(--primary-300-rgb), 0.1);
        color: var(--white-500);
    }

    .btn-modern {
        border-radius: 50px;
        padding: 0.6rem 1.5rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .role-badge {
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
</style>
@endpush
