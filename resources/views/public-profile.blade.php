@extends('layouts.app')
@section('title', $user->first_name ?? 'Player Profile')

@section('content')
<div class="container py-5 text-white">
  <div class="row justify-content-center">
    <div class="col-lg-10">

      {{-- ===== Avatar Header ===== --}}
      <div class="text-center mb-4">
        <div class="avatar-square mx-auto mb-3" style="--avatar-size:180px;">
          @if($photoUrl)
            <img src="{{ $photoUrl }}" class="avatar-img" alt="Profile">
          @else
            <div class="avatar-fallback d-flex align-items-center justify-content-center">
              <span class="avatar-initials-text">{{ $initials }}</span>
            </div>
          @endif
        </div>
        <h3 class="fw-bold text-primary-500">{{ $user->first_name }} {{ $user->last_name }}</h3>
        <p class="text-muted">@{{ $user->username }}</p>
      </div>

    {{-- ===== Grafik 0: Yearly Trend ===== --}}
    <div class="card bg-black-200 shadow rounded-lg p-4 mb-4">
        <h6 class="text-primary-500 fw-semibold text-center mb-3">Yearly Match Activity ({{ now()->year }})</h6>
        <canvas id="chartYearly" height="150"></canvas>
    </div>


      {{-- ===== Grafik 1: Weekly Matches ===== --}}
      <div class="card bg-black-200 shadow rounded-lg p-4 mb-4">
        <h6 class="text-primary-500 fw-semibold text-center mb-3">Matches Played (Last 7 Days)</h6>
        <canvas id="chartMatches" height="130"></canvas>
      </div>

      {{-- ===== Grafik 2: Stroke Trend (4 Lines) ===== --}}
      <div class="card bg-black-200 shadow rounded-lg p-4 mb-4">
        <h6 class="text-primary-500 fw-semibold text-center mb-3">Stroke Trend (Last 3 Months)</h6>
        <canvas id="chartStroke" height="180"></canvas>

        <div class="text-center mt-4">
          <h6 class="text-white-400 mb-1">Total Playtime</h6>
          <p class="fw-semibold text-primary-500">{{ $playtime }}</p>
        </div>
      </div>

      <div class="text-center mt-3">
        <small class="text-white-400">
          Member since {{ $user->created_at?->format('F Y') ?? '2024' }} • CourtPlay
        </small>
      </div>
    </div>
  </div>
</div>

{{-- ===== ChartJS ===== --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {

  // === Grafik 1: Weekly Matches ===
  const ctx1 = document.getElementById('chartMatches');
  new Chart(ctx1, {
    type: 'line',
    data: {
      labels: @json($weeklyMatches->keys()),
      datasets: [{
        label: 'Matches',
        data: @json($weeklyMatches->values()),
        borderColor: '#a3ce14',
        backgroundColor: 'rgba(163,206,20,0.15)',
        borderWidth: 3,
        tension: 0.3,
        fill: true,
        pointRadius: 3,
        pointBackgroundColor: '#a3ce14'
      }]
    },
    options: {
      responsive: true,
      plugins: { legend: { display: false } },
      scales: {
        y: { beginAtZero: true, ticks: { color: '#999' }, grid: { color: '#333' } },
        x: { ticks: { color: '#999' }, grid: { color: '#222' } }
      }
    }
  });

  // === Grafik 2: Stroke Trend ===
  const ctx2 = document.getElementById('chartStroke');
  new Chart(ctx2, {
    type: 'line',
    data: {
      labels: @json($labels),
      datasets: [
        { label: 'Forehand', data: @json($forehandData), borderColor: '#a3ce14', borderWidth: 3, tension: 0.3, fill: false },
        { label: 'Backhand', data: @json($backhandData), borderColor: '#d9ff6f', borderWidth: 3, tension: 0.3, fill: false },
        { label: 'Serve', data: @json($serveData), borderColor: '#77ffb3', borderWidth: 3, tension: 0.3, fill: false },
        { label: 'Ready', data: @json($readyData), borderColor: '#66ccff', borderWidth: 3, tension: 0.3, fill: false },
      ]
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          labels: { color: '#ccc', boxWidth: 12, usePointStyle: true },
          position: 'top',
          align: 'end'
        },
        tooltip: { enabled: true }
      },
      scales: {
        y: { beginAtZero: true, ticks: { color: '#ccc' }, grid: { color: '#444' } },
        x: { ticks: { color: '#ccc' }, grid: { color: '#333' } }
      }
    }
  });

    // === Grafik 0: Yearly Match Activity ===
const ctx0 = document.getElementById('chartYearly');
const ctx0Ctx = ctx0.getContext('2d');

// Buat gradient warna hijau muda → lime
const gradient = ctx0Ctx.createLinearGradient(0, 0, 0, 180);
gradient.addColorStop(0, '#a3ce14');
gradient.addColorStop(1, '#77ffb3');

// Batasi tinggi agar tidak terlalu panjang
ctx0.style.maxHeight = '180px';
ctx0.style.height = '180px';

Chart.defaults.animation.duration = 1000;
Chart.defaults.animation.easing = 'easeOutQuart';

const chartYearly = new Chart(ctx0Ctx, {
  type: 'bar',
  data: {
    labels: @json($monthLabels),
    datasets: [{
      label: 'Matches per Month',
      data: @json($monthlyMatches),
      backgroundColor: gradient,
      borderColor: '#a3ce14',
      borderWidth: 1,
      borderRadius: 4,
      borderSkipped: false,
      barPercentage: 0.45,
      categoryPercentage: 0.8
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: true, // ✅ tetap proporsional
    aspectRatio: 3.5,          // ✅ kontrol perbandingan lebar vs tinggi
    plugins: {
      legend: { display: false },
      tooltip: {
        callbacks: {
          label: (ctx) => `Matches: ${ctx.formattedValue}`
        }
      }
    },
    scales: {
      y: {
        beginAtZero: true,
        ticks: { color: '#aaa', stepSize: 5 },
        grid: { color: '#333' }
      },
      x: {
        ticks: { color: '#aaa' },
        grid: { display: false }
      }
    }
  }
});





});
</script>
@endsection
