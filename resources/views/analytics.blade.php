@extends('layouts.app')

@section('title', 'Analytics Dashboard')
@section('fullbleed', true)

@section('content')
    {{-- Sisipkan komponen Livewire --}}
    <livewire:analytics-dashboard />
@endsection

@push('scripts')
@endpush

@push('styles')
<style>
    /* Modern Analytics Styles */
    .analytics-card {
        background: rgba(255, 255, 255, 0.03);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 16px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .analytics-card:hover {
        background: rgba(255, 255, 255, 0.05);
        border-color: rgba(var(--primary-300-rgb), 0.3);
        transform: translateY(-2px);
        box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.5);
    }

    .stat-card {
        background: rgba(255, 255, 255, 0.02);
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 20px;
        padding: 1.5rem;
        height: 100%;
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        background: rgba(255, 255, 255, 0.04);
        border-color: rgba(var(--primary-300-rgb), 0.2);
    }

    .project-item {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.1);
        transition: all 0.3s ease;
    }

    .project-item:hover {
        background: rgba(255, 255, 255, 0.06);
        border-color: var(--primary-300);
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.2);
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

    .badge-modern {
        padding: 0.5em 1em;
        border-radius: 50px;
        font-weight: 500;
        letter-spacing: 0.5px;
    }

    /* Gauge Styles */
    .gauge-bg {
        fill: none;
        stroke: rgba(255, 255, 255, 0.1);
        stroke-width: 8;
        stroke-linecap: round;
    }
    
    #gauge-arc {
        fill: none;
        stroke: var(--primary-300);
        stroke-width: 8;
        stroke-linecap: round;
        filter: drop-shadow(0 0 5px rgba(var(--primary-300-rgb), 0.5));
    }
</style>
@endpush
