@extends('layouts.app')

@section('title', 'Social Page')
@section('fullbleed', true)

@section('content')
<div class="container py-5 text-white">
  <div class="row justify-content-center">
    <div class="col-lg-10">

      <!-- Form Pencarian Pengguna -->
      <div class="mb-4">
        <h1 class="fw-bold text-primary-100">Explore Users</h1>
        <form action="{{ route('social') }}" method="GET" class="d-flex mb-4">
          <input type="text" name="search" class="form-control me-2" placeholder="Search by username, first name, or last name" value="{{ old('search', $searchTerm) }}">
          <button type="submit" class="btn btn-primary">Search</button>
        </form>
      </div>

      <!-- 5 User dengan Followers Terbanyak -->
      <div class="mb-5">
        <h2 class="fw-bold text-primary-100">Top 5 Users with Most Followers</h2>
        <ul class="list-unstyled">
          @foreach ($topFollowers as $user)
            <li class="mb-3 d-flex justify-content-between align-items-center">
              <a href="{{ route('user.profile', $user->username) }}" class="text-primary-500">{{ $user->username }}</a>
              <span class="text-white-400">{{ $user->followers_count ?? 0 }} Followers</span>
              <span class="text-white-400">{{ $user->following_count ?? 0 }} Following</span>

              <!-- Tombol Follow/Unfollow -->
              @if(auth()->check() && auth()->user()->id !== $user->id)
                <form action="{{ route('user.toggleFollow', $user->username) }}" method="POST" class="d-inline">
                  @csrf
                  @if(auth()->user()->isFollowing($userId, $user->id))
                    <!-- Tombol Unfollow jika sudah mengikuti -->
                    <button type="submit" class="btn btn-danger">Unfollow</button>
                  @else
                    <!-- Tombol Follow jika belum mengikuti -->
                    <button type="submit" class="btn btn-primary">Follow</button>
                  @endif
                </form>
              @endif
            </li>
          @endforeach
        </ul>
      </div>

      <!-- 5 User Terbaru -->
      <div class="mb-5">
        <h2 class="fw-bold text-primary-100">Top 5 Latest Users</h2>
        <ul class="list-unstyled">
          @foreach ($latestUsers as $user)
            <li class="mb-3 d-flex justify-content-between align-items-center">
              <a href="{{ route('user.profile', $user->username) }}" class="text-primary-500">{{ $user->username }}</a>
              <span class="text-white-400">Joined on {{ $user->created_at->format('F d, Y') }}</span>
            </li>
          @endforeach
        </ul>
      </div>

      <!-- Hasil Pencarian -->
      @if($users->count() > 0)
        <div class="mb-5">
          <h2 class="fw-bold text-primary-100">Search Results</h2>
          <ul class="list-unstyled">
            @foreach ($users as $user)
              <li class="mb-3">
                <a href="{{ route('user.profile', $user->username) }}" class="text-primary-500">{{ $user->username }}</a> -
                {{ $user->first_name }} {{ $user->last_name }}
              </li>
            @endforeach
          </ul>
        </div>
      @else
        <p class="text-white-400">No users found matching your search.</p>
      @endif

    </div>
  </div>
</div>
@endsection
