@extends('layouts.app-admin')
@section('title','Admin • Users')
@section('page_title','Users')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <form class="d-flex" method="get">
    <input name="q" class="form-control me-2" type="search" placeholder="Search name/username/email…" value="{{ $q }}">
    <button class="btn btn-outline-secondary">Search</button>
  </form>


  <div class="d-flex flex-wrap gap-2">
    <span class="badge bg-primary-300 text-black fw-semibold">Free: {{ $roleCounts['free'] ?? 0 }}</span>
    <span class="badge bg-info text-black fw-semibold">Pro: {{ $roleCounts['pro'] ?? 0 }}</span>
    <span class="badge bg-warning text-black fw-semibold">Plus: {{ $roleCounts['plus'] ?? 0 }}</span>
    <span class="badge bg-danger text-black fw-semibold">Admin: {{ $roleCounts['admin'] ?? 0 }}</span>
  </div>
</div>

<div class="table-responsive">
  <table class="table table-dark table-hover align-middle">
    <thead>
      <tr>
        <th>Name</th>
        <th>Username / Email</th>
        <th style="width:180px">Role</th>
        <th>Joined</th>
        <th class="text-end">Actions</th>
      </tr>
    </thead>
    <tbody>
    @forelse($users as $u)
      <tr>
        <td>{{ trim(($u->first_name ?? '').' '.($u->last_name ?? '')) ?: '—' }}</td>
        <td>
          <div class="fw-semibold">{{ $u->username }}</div>
          <div class="text-white-50 small">{{ $u->email }}</div>
        </td>
        <td>
          <form action="{{ route('admin.users.role',$u) }}" method="POST" class="m-0 p-0 role-form">
            @csrf @method('PATCH')
            <select name="role" class="form-select form-select-sm bg-black-200 text-white" onchange="this.form.submit()">
              @php $roles=['free','pro','plus','admin']; @endphp
              @foreach($roles as $r)
                <option value="{{ $r }}" {{ strtolower($u->role ?? 'free')===$r?'selected':'' }}>{{ ucfirst($r) }}</option>
              @endforeach
            </select>
          </form>
        </td>
        <td class="text-white-50">{{ $u->created_at?->format('Y-m-d') }}</td>
        <td class="text-end">
          <form action="{{ route('admin.users.destroy',$u) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this user and ALL related data?')">
            @csrf @method('DELETE')
            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i> Delete</button>
          </form>
        </td>
      </tr>
    @empty
      <tr><td colspan="5" class="text-center text-white-50">No users</td></tr>
    @endforelse
    </tbody>
  </table>
</div>

<div class="mt-3">{{ $users->links() }}</div>
@endsection
