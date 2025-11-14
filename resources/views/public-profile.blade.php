@extends('layouts.app')

@section('title', $user->first_name ?? 'Player Profile')

@section('content')
<div class="container py-5 text-white">
  <div class="row justify-content-center">
    <!-- Left Column: Profile Info -->
    <div class="col-lg-3 mb-4">
      <div class=" card text-center bg-black-200 shadow rounded-lg p-4 mb-4">
        <div class="avatar-square position-relative mx-auto overflow-hidden mb-3" style="--avatar-size:180px;">
          @if($photoUrl)
            <img src="{{ $photoUrl }}" class="avatar-img" alt="Profile">
          @else
            <div class="avatar-fallback d-flex align-items-center justify-content-center">
              <span class="avatar-initials-text">{{ $initials }}</span>
            </div>
          @endif
        </div>
        <h3 class="fw-bold text-primary-500">{{ $user->first_name }} {{ $user->last_name }}</h3>

        <p class="text-primary-500">@ {{ $user->username }}</p>
      </div>

      <!-- Followers and Following -->
      <div class="d-flex justify-content-center mb-3">
        <div class="me-4"><strong>{{ $followersCount ?? 0 }}</strong> Followers</div>
        <div><strong>{{ $followingCount ?? 0 }}</strong> Following</div>
      </div>

      <!-- Follow/Unfollow Button -->
      @if(auth()->check())
        @if(auth()->user()->id !== $user->id)
          <div class="d-flex justify-content-center">
            <form id="followForm" action="{{ route('user.follow', $user->username) }}" method="POST" data-action="{{ route('user.follow', $user->username) }}">
                @csrf
                <button type="submit" id="followBtn" class="btn {{ $isFollowing ? 'btn-custom2' : 'btn-custom' }}">
                    {{ $isFollowing ? 'Unfollow' : 'Follow' }}
                </button>
            </form>
        </div>
        @endif
      @else
        <div class="d-flex justify-content-center">
          <a href="{{ route('login') }}" class="btn btn-custom">Follow</a>
        </div>
      @endif
    </div>

    <!-- Center Column: Projects -->
    <div class="col-lg-6 mb-4">

      @foreach($projects->take(3) as $project)
        <div class="card mb-3 bg-black-200 shadow rounded-lg p-4">

      <!-- Project Header (Two Columns) -->
      <div class="row">
        <!-- Left Column: Project Name and Date -->
        <div class="col-6">
          <div class="text-primary-500 small">
            {{ \Carbon\Carbon::parse($project->upload_date)->format('M d, Y') }}
          </div>
          <h3 class="fw-semibold text-primary-300">{{ $project->project_name }}</h3>

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

    <!-- Right Column: Stats -->
    <div class="col-lg-3 mb-4">
        <div class="card bg-black-200 shadow rounded-lg p-4 mb-4 text-center">
            <h3 class="text-primary-500 fw-semibold mb-3">Project Stats</h3>

            <div class="row">
                <!-- Left Column: Total Projects -->
                <div class="col-6">
                    <h5 class="fw-semibold text-primary-300">{{ $totalProjects }}</h5>
                    <p class="text-primary-500 fw-semibold mb-3">Total Projects</p>
                </div>

                <!-- Right Column: Playing Time -->
                <div class="col-6">
                    <h5 class="fw-semibold text-primary-300">{{ $playtime }}</h5>
                    <p class="text-primary-500 fw-semibold mb-3">Playing Time</p>
                </div>
            </div>
        </div>
      <div class="card bg-black-200 shadow rounded-lg p-4 mb-4">
        <h6 class="text-primary-500 fw-semibold text-center mb-3">Project History (Last 6 Months)</h6>
        <canvas id="projectHistoryChart" height="180"></canvas>
      </div>

      <div class="card bg-black-200 shadow rounded-lg p-4 mt-4">
        <h6 class="text-primary-500 fw-semibold text-center mb-3">Stroke Trend (Last 3 Months)</h6>
        <canvas id="strokeTrendChart" height="200"></canvas>
      </div>
    </div>
  </div>
</div>

{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {

  // Spider charts for each project
  @foreach($projects->take(3) as $project)
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

  // Stroke Trend (3 months)
  new Chart(document.getElementById('strokeTrendChart'), {
    type: 'line',
    data: {
      labels: @json($labels),
      datasets: [
        { label: 'Forehand', data: @json($forehandData), borderColor: '#a3ce14', borderWidth: 2, tension: 0.3 },
        { label: 'Backhand', data: @json($backhandData), borderColor: '#d9ff6f', borderWidth: 2, tension: 0.3 },
        { label: 'Serve', data: @json($serveData), borderColor: '#77ffb3', borderWidth: 2, tension: 0.3 },
        { label: 'Ready', data: @json($readyData), borderColor: '#66ccff', borderWidth: 2, tension: 0.3 },
      ]
    },
    options: {
      responsive: true,
      plugins: { legend: { labels: { color: '#ccc' } } },
      scales: {
        x: { ticks: { color: '#aaa' }, grid: { color: '#333' } },
        y: { beginAtZero: true, ticks: { color: '#aaa' }, grid: { color: '#333' } }
      }
    }
  });


  // Project History (6 months)
  new Chart(document.getElementById('projectHistoryChart'), {
    type: 'bar',
    data: {
      labels: @json($monthlyProjectCounts->keys() ?? []),
      datasets: [{
        label: 'Projects',
        data: @json($monthlyProjectCounts->values() ?? []),
        backgroundColor: '#a3ce14',
        borderRadius: 4
      }]
    },
    options: {
      responsive: true,
      scales: {
        y: { beginAtZero: true, ticks: { color: '#aaa' }, grid: { color: '#333' } },
        x: { ticks: { color: '#aaa' }, grid: { display: false } }
      },
      plugins: { legend: { display: false } }
    }
  });

});

document.addEventListener('DOMContentLoaded', () => {
    const followForm = document.getElementById('followForm');
    const followBtn = document.getElementById('followBtn');

    followForm.addEventListener('submit', function (e) {
        e.preventDefault(); // Prevent the default form submission

        const url = followForm.getAttribute('data-action');
        const method = followForm.method;
        const formData = new FormData(followForm);

        // Make an Ajax request
        fetch(url, {
            method: method,
            body: formData,
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update the button text based on the follow status
                followBtn.textContent = data.isFollowing ? 'Unfollow' : 'Follow';
                followBtn.classList.toggle('btn-custom', !data.isFollowing);
                followBtn.classList.toggle('btn-custom2', data.isFollowing);
            } else {
                alert('An error occurred. Please try again.');
            }
        })
        .catch(error => {
            alert('An error occurred. Please try again.');
        });
    });
});
</script>
@endsection
