@extends('layouts.app-admin')
@section('title','Admin • Dashboard')
@section('page_title','Dashboard')

@section('content')

{{-- ===== PROJECT TRENDS ===== --}}
<div class="admin-card mb-4">
  <div class="admin-card-header">
    <span class="text-primary-300"><i class="bi bi-graph-up-arrow me-2"></i>Project Trends</span>
    <div class="btn-group btn-group-sm btn-group-modern" role="group">
      <button class="btn" data-range="7d">1W</button>
      <button class="btn" data-range="14d">2W</button>
      <button class="btn" data-range="30d">1M</button>
      <button class="btn" data-range="365d">1Y</button>
    </div>
  </div>
  <div class="card-body chart-wrap p-4"><canvas id="projectsTrend"></canvas></div>
</div>

{{-- ===== CLOUD RUN METRICS ===== --}}
<div class="admin-card mb-4">
  <div class="admin-card-header">
    <span class="text-primary-300"><i class="bi bi-cloud-check me-2"></i>Cloud Run Metrics</span>
  </div>
  <div class="card-body p-4" style="height:300px">
    <canvas id="cloudRunMetrics"></canvas>
  </div>
</div>

{{-- ===== STATS CARDS ===== --}}
<div class="row g-4 mb-4">
  @php
    $cards = [
      ['label'=>'Total Users','value'=>$stats['users']??0, 'icon'=>'bi-people'],
      ['label'=>'Admins','value'=>$stats['admins']??0, 'icon'=>'bi-shield-lock'],
      ['label'=>'Projects','value'=>$stats['projects']??0, 'icon'=>'bi-collection-play'],
    ];
  @endphp

  @foreach($cards as $card)
  <div class="col-md-3">
    <div class="admin-card h-100">
      <div class="card-body p-4 d-flex flex-column justify-content-between h-100">
        <div class="d-flex justify-content-between align-items-start mb-3">
            <div class="stat-label">{{ $card['label'] }}</div>
            <i class="bi {{ $card['icon'] }} fs-4 text-white-50"></i>
        </div>
        <div class="stat-value">{{ number_format($card['value']) }}</div>
      </div>
    </div>
  </div>
  @endforeach

  <div class="col-md-3">
    <div class="admin-card h-100">
      <div class="card-body p-4 d-flex flex-column justify-content-between h-100">
        <div class="d-flex justify-content-between align-items-start mb-3">
            <div class="stat-label">Status</div>
            <i class="bi bi-pie-chart fs-4 text-white-50"></i>
        </div>
        <div class="d-flex flex-column gap-2">
            <div class="d-flex justify-content-between align-items-center">
                <span class="text-white-400">Completed</span>
                <span class="badge bg-success rounded-pill">{{ $stats['completed'] ?? 0 }}</span>
            </div>
            <div class="d-flex justify-content-between align-items-center">
                <span class="text-white-400">In Progress</span>
                <span class="badge bg-warning text-dark rounded-pill">{{ $stats['in_progress'] ?? 0 }}</span>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- ===== TOP USERS ===== --}}
<div class="admin-card">
  <div class="admin-card-header">
    <span class="text-primary-300"><i class="bi bi-trophy me-2"></i>Top Users by Projects</span>
  </div>
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-modern mb-0">
        <thead><tr><th>User</th><th>Username</th><th class="text-end">Projects</th></tr></thead>
        <tbody>
          @forelse(($topUsers ?? []) as $u)
            <tr>
              <td>
                <div class="d-flex align-items-center">
                    <div class="rounded-circle bg-dark d-flex align-items-center justify-content-center me-3" style="width: 32px; height: 32px;">
                        <span class="text-primary-300 fw-bold">{{ substr($u->first_name, 0, 1) }}</span>
                    </div>
                    {{ trim(($u->first_name ?? '').' '.($u->last_name ?? '')) ?: '—' }}
                </div>
              </td>
              <td class="text-white-50">{{ $u->username }}</td>
              <td class="text-end fw-bold text-primary-300">{{ $u->projects_count }}</td>
            </tr>
          @empty
            <tr><td colspan="3" class="text-center text-white-50 py-4">No data available</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection


@php
  $trend = $trend ?? ['7d'=>['labels'=>[],'data'=>[]],'14d'=>['labels'=>[],'data'=>[]],'30d'=>['labels'=>[],'data'=>[]],'365d'=>['labels'=>[],'data'=>[]]];
@endphp

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function(){

  // ==== Project Trends ====
  const trend = @json($trend);
  const ctxTrend = document.getElementById('projectsTrend').getContext('2d');
  const grad = ctxTrend.createLinearGradient(0,0,0,230);
  grad.addColorStop(0,'rgba(163,206,20,0.6)');
  grad.addColorStop(1,'rgba(163,206,20,0.05)');

  const chartTrend = new Chart(ctxTrend,{
    type:'line',
    data:{labels:trend['7d'].labels,datasets:[{data:trend['7d'].data,label:'Projects',borderColor:'#a3ce14',backgroundColor:grad,fill:true,tension:.35}]},
    options:{responsive:true,maintainAspectRatio:false,scales:{x:{ticks:{color:'#fafafa'}},y:{ticks:{color:'#fafafa'}}}}
  });

  document.querySelectorAll('[data-range]').forEach(b=>b.addEventListener('click',()=>{
    document.querySelectorAll('[data-range]').forEach(x=>x.classList.remove('active'));
    b.classList.add('active');
    const k=b.dataset.range;
    chartTrend.data.labels=trend[k].labels;
    chartTrend.data.datasets[0].data=trend[k].data;
    chartTrend.update();
  }));

  // ==== Cloud Run Metrics ====
    const reqData = @json($requestMetrics[0]['points'] ?? []);
    const latData = @json($latencyMetrics[0]['points'] ?? []);
    const instData = @json($instanceMetrics[0]['points'] ?? []);

    const labels = reqData.map(p => p.time);

    new Chart(document.getElementById('cloudRunMetrics').getContext('2d'), {
    type: 'line',
    data: {
        labels,
        datasets: [
        {
            label: 'Requests/hour',
            data: reqData.map(p => p.value),
            borderColor: '#66ff99',
            borderWidth: 2, tension: 0.3, pointRadius: 0
        },
        {
            label: 'Latency (ms)',
            data: latData.map(p => p.value),
            borderColor: '#ffcc00',
            borderWidth: 2, tension: 0.3, pointRadius: 0
        },
        {
            label: 'Instances',
            data: instData.map(p => p.value),
            borderColor: '#3399ff',
            borderWidth: 2, tension: 0.3, pointRadius: 0
        }
        ]
    },
    options: { responsive:true, maintainAspectRatio:false }
    });

});
</script>
@endpush

@push('styles')
<style>
    /* Modern Admin Dashboard Styles */
    .admin-card {
        background: rgba(255, 255, 255, 0.03);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 16px;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .admin-card:hover {
        border-color: rgba(var(--primary-300-rgb), 0.3);
        box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.5);
    }

    .admin-card-header {
        background: rgba(255, 255, 255, 0.02);
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        padding: 1rem 1.5rem;
        font-weight: 600;
        color: var(--white-500);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .stat-value {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--primary-300);
        line-height: 1.2;
    }

    .stat-label {
        color: var(--white-400);
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 1px;
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
    }

    .table-modern td {
        padding: 1rem 1.5rem;
        vertical-align: middle;
    }

    .btn-group-modern .btn {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: var(--white-400);
    }

    .btn-group-modern .btn:hover, .btn-group-modern .btn.active {
        background: var(--primary-300);
        color: #000;
        border-color: var(--primary-300);
    }
</style>
@endpush
