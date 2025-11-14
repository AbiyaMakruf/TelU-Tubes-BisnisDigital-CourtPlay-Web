@extends('layouts.app')

@section('title', 'Social Page')
@section('fullbleed', true)

@section('content')
<div class="container py-5 text-white">
  <div class="row justify-content-center">
    <!-- Left Column: Search Users -->
    <div class="col-lg-3 mb-4 justify-content-center">
      <!-- Search Form -->
      <div class="card text-center bg-black-200 shadow rounded-lg p-3 mb-3">
        <h4 class="fw-bold text-primary-300">Explore Users</h4>
        <form action="{{ route('social') }}" method="GET" class="d-flex mb-4">
          <input type="text" name="search" class="form-control me-2 input-custom" placeholder="Search by username, first name, or last name" value="{{ old('search', $searchTerm) }}">
          <button type="submit" class="btn btn-custom3 w-25"><i class="bi bi-search"></i></button>
        </form>
      </div>

      @if($searchTerm)
        <!-- Display Users Found (when search term is entered) -->
        <div class="mb-5">
          <h5 class="fw-bold text-primary-300">Search Results</h5>
          <ul class="list-unstyled">
            @foreach ($users as $user)
              <li class="mb-3 d-flex justify-content-between align-items-center">
                <a href="{{ route('user.profile', $user->username) }}" class="text-primary-500">{{ $user->username }}</a>
                <div class="d-flex justify-content-start">
                  <div class="d-flex flex-column pe-3 text-center">
                  </div>
                  <div class="d-flex flex-column text-center">
                    @if(auth()->check() && auth()->user()->id !== $user->id)
                      <form action="{{ route('user.toggleFollow', $user->username) }}" method="POST" class="d-inline">
                        @csrf
                        @if(auth()->user()->isFollowing($userId, $user->id))
                          <button type="submit" class="btn btn-danger btn-sm">Unfollow</button>
                        @else
                          <button type="submit" class="btn btn-primary btn-sm">Follow</button>
                        @endif
                      </form>
                    @endif
                  </div>
                </div>
              </li>
            @endforeach
          </ul>
        </div>
      @else
        <!-- Popular Users (only shown when no search term is entered) -->
        <div class="card text-center bg-black-200 shadow rounded-lg p-4 mb-4">
          <h4 class="fw-bold text-primary-300 d-flex justify-content-start align-items-center mb-3">Popular</h4>
          <ul class="list-unstyled">
            @foreach ($topFollowers as $user)
            <li class="mb-3 d-flex justify-content-start">
                <!-- Profile Circle (Links to public profile) -->
                <a href="{{ route('user.profile', $user->username) }}" class="d-flex align-items-center">
                <div class="avatar-circle">
                    @if($user->profile_picture_url) <!-- Check if user has a profile picture -->
                    <img src="{{ $user->profile_picture_url }}" alt="{{ $user->username }}" class="avatar-img2">
                    @else
                    <!-- Display initials inside the circle if no profile picture -->
                    <span class="avatar-initials-text2">
                        {{ strtoupper(substr($user->first_name, 0, 1) . substr($user->last_name, 0, 1)) }}
                    </span>
                    @endif
                </div>
                </a>

                <!-- User Username and Followers Count (Displayed horizontally) -->
                <div class="ms-3 ">
                <a href="{{ route('user.profile', $user->username) }}" class="text-primary-500 d-flex justify-content-start align-items-center">
                    <span class="fw-semibold">{{ $user->username }}</span>
                </a>
                <div class="small text-primary-300 d-flex justify-content-start">{{ $user->followers_count }} Followers</div>
                </div>
            </li>
            @endforeach
          </ul>
        </div>

        <!-- Latest Users -->
        <div class="card text-center bg-black-200 shadow rounded-lg p-4 mb-4">
        <h4 class="fw-bold text-primary-300 d-flex justify-content-start align-items-center mb-3">New Coming</h4>
        <ul class="list-unstyled">
            @foreach ($latestUsers as $user)
            <li class="mb-3 d-flex justify-content-start">
                <!-- Profile Circle (Links to public profile) -->
                <a href="{{ route('user.profile', $user->username) }}" class="d-flex align-items-center">
                <div class="avatar-circle">
                    @if($user->profile_picture_url) <!-- Check if user has a profile picture -->
                    <img src="{{ $user->profile_picture_url }}" alt="{{ $user->username }}" class="avatar-img2">
                    @else
                    <!-- Display initials inside the circle if no profile picture -->
                    <span class="avatar-initials-text2">
                        {{ strtoupper(substr($user->first_name, 0, 1) . substr($user->last_name, 0, 1)) }}
                    </span>
                    @endif
                </div>
                </a>

                <!-- User Username and Followers Count (Displayed horizontally) -->
                <div class="ms-3">
                <a href="{{ route('user.profile', $user->username) }}" class="text-primary-500 d-flex justify-content-start align-items-center">
                    <span class="fw-semibold">{{ $user->username }}</span>
                </a>
                <div class="small text-primary-300 d-flex justify-content-start">Joined on {{ $user->created_at->format('M d, Y') }}</div>
                </div>
            </li>
            @endforeach
        </ul>
        </div>
      @endif
    </div>

    <!-- Center Column: Projects -->
    <div class="col-lg-9 mb-4">
      @foreach($latestProjects as $project)
        <div class="card mb-3 bg-black-200 shadow rounded-lg p-4">

      <!-- Project Header (Two Columns) -->
      <div class="row">
        <!-- Left Column: Project Name and Date -->
        <div class="col-6">
          <div class="text-primary-500 small">
            {{ \Carbon\Carbon::parse($project->upload_date)->format('M d, Y') }}
          </div>
          <h3 class="fw-semibold text-primary-300 mb-0">{{ $project->project_name }}</h3>
        <p class=" text-white-400"><a href="{{ route('user.profile', $project->user->username) }}" class="text-primary-500">{{ $project->user->username }}</a></p> <!-- Display project owner username -->


        </div>

        <!-- Right Column: Time and Major Movement -->
        <div class="col-6 text-end">
          <div class="d-flex justify-content-end">
            <div class="d-flex flex-column pe-3">
              <span class="small text-primary-500">Time</span>
              <span class="fw-semibold fs-5 text-primary-500">
                {{ gmdate('H:i:s', $project->projectDetails->video_duration ?? 0) }}
              </span>
            </div>

            <div class="d-flex flex-column border-start ps-3">
              <span class="small text-primary-500">Major Movement</span>
              <span class="fw-semibold fs-5 text-primary-500">
                {{ $project->major_movement ?? 'Balanced' }}
              </span>
            </div>
          </div>
        </div>
      </div>

      <!-- Project Details (Spider Chart, Thumbnail, Heatmap) -->
      <div class="d-flex mt-3">
        <!-- Spider Chart -->
        <div class="col-6 pe-2">
          <canvas id="spiderChart{{ $project->id }}" height="200"></canvas>
        </div>

        <!-- Right: Thumbnail + Player Heatmap -->
        <div class="col-6 ps-2">
          <div class="row">
            <div class="col-12 mb-2">
              <img src="{{ $project->link_image_thumbnail }}" class="img-fluid rounded" alt="Thumbnail">
            </div>
            <div class="col-12">
              <img src="{{ $project->link_image_heatmap_player }}" class="img-fluid rounded" alt="Heatmap">
            </div>
          </div>
        </div>
      </div>
    </div>
      @endforeach
    </div>
  </div>
</div>


{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {

  // Spider charts for each project
  @foreach($latestProjects as $project)
    new Chart(document.getElementById('spiderChart{{ $project->id }}'), {
        type: 'radar',
        data: {
            labels: ['Forehand', 'Backhand', 'Serve', 'Ready'],
            datasets: [{
            label: '{{ $project->name }}',
            data: [
                {{ $project->projectDetails->forehand_count ?? 0 }},
                {{ $project->projectDetails->backhand_count ?? 0 }},
                {{ $project->projectDetails->serve_count ?? 0 }},
                {{ $project->projectDetails->ready_position_count ?? 0 }}
            ],
            borderColor: '#a3ce14',
            backgroundColor: 'rgba(163,206,20,0.2)',
            borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            scales: {
            r: {
                angleLines: { color: '#333' },
                grid: { color: '#444' },
                pointLabels: { color: '#ccc' },
                ticks: {
                display: false, // Hide scale ticks
                color: '#888',
                beginAtZero: true
                }
            }
            },
            plugins: {
            legend: {
                display: false, // This removes the entire legend, including the label boxes
            }
            }
        }
    });
  @endforeach

});
</script>
@endsection
