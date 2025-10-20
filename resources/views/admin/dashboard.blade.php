@extends('layouts.app-admin')
@section('title','Admin • Dashboard')
@section('page_title','Dashboard')

@section('content')

{{-- ===== PROJECT TRENDS ===== --}}
<div class="card bg-transparent border-secondary mb-4">
  <div class="card-header border-secondary d-flex justify-content-between align-items-center">
    <span class="fw-semibold text-primary-500">Project Trends</span>
    <div class="btn-group btn-group-sm" role="group">
      <button class="btn btn-outline-secondary active" data-range="7d">1W</button>
      <button class="btn btn-outline-secondary" data-range="14d">2W</button>
      <button class="btn btn-outline-secondary" data-range="30d">1M</button>
      <button class="btn btn-outline-secondary" data-range="365d">1Y</button>
    </div>
  </div>
  <div class="card-body chart-wrap"><canvas id="projectsTrend"></canvas></div>
</div>

{{-- ===== CLOUD RUN METRICS ===== --}}
<div class="card bg-transparent border-secondary mb-4">
  <div class="card-header border-secondary fw-semibold text-primary-500">
    Cloud Run Metrics
  </div>
  <div class="card-body" style="height:300px">
    <canvas id="cloudRunMetrics"></canvas>
  </div>
</div>

{{-- ===== STATS CARDS ===== --}}
<div class="row g-3">
  @php
    $cards = [
      ['label'=>'Total Users','value'=>$stats['users']??0],
      ['label'=>'Admins','value'=>$stats['admins']??0],
      ['label'=>'Projects','value'=>$stats['projects']??0],
      ['label'=>'Status','custom'=>true],
    ];
  @endphp

  @foreach($cards as $card)
  <div class="col-md-3 d-flex">
    <div class="card flex-fill bg-transparent border-secondary">
      <div class="card-body d-flex flex-column justify-content-between">
        @if(!empty($card['custom']))
          <div>
            <div class="text-white-50 small">{{ $card['label'] }}</div>
            <div class="d-flex gap-3 mt-1 mb-3 flex-wrap">
              <span class="badge bg-success">Completed {{ $stats['completed'] ?? 0 }}</span>
              <span class="badge bg-secondary">In Progress {{ $stats['in_progress'] ?? 0 }}</span>
            </div>
          </div>
        @else
          <div>
            <div class="text-white-50 small">{{ $card['label'] }}</div>
            <div class="fs-3 fw-bold text-primary-500">{{ number_format($card['value']) }}</div>
          </div>
        @endif
      </div>
    </div>
  </div>
  @endforeach
</div>

{{-- ===== TOP USERS ===== --}}
<div class="card bg-transparent border-secondary mt-4">
  <div class="card-header border-secondary text-primary-500 fw-semibold">Top Users by Projects</div>
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-dark table-hover mb-0 align-middle">
        <thead><tr><th>User</th><th>Username</th><th class="text-end">Projects</th></tr></thead>
        <tbody>
          @forelse(($topUsers ?? []) as $u)
            <tr>
              <td>{{ trim(($u->first_name ?? '').' '.($u->last_name ?? '')) ?: '—' }}</td>
              <td class="text-white-50">{{ $u->username }}</td>
              <td class="text-end fw-semibold text-primary-500">{{ $u->projects_count }}</td>
            </tr>
          @empty
            <tr><td colspan="3" class="text-center text-white-50">No data</td></tr>
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
