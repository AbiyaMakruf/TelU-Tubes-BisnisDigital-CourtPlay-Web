@extends('layouts.app')

@section('title', 'Analytics Detail')
@section('fullbleed', true)

@section('content')
    <livewire:analytics-details :projectId="$project->id" />
@endsection

@push('scripts')
@endpush

@push('styles')
<style>
    /* Modern Analytics Detail Styles */
    .glass-card {
        background: rgba(255, 255, 255, 0.03);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 20px;
        overflow: hidden;
    }

    .glass-header {
        background: rgba(255, 255, 255, 0.02);
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        padding: 1rem 1.5rem;
    }

    .video-container {
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        border: 1px solid rgba(255, 255, 255, 0.1);
        background: #000;
    }

    .placeholder-modern {
        background: rgba(255, 255, 255, 0.02);
        border: 1px dashed rgba(255, 255, 255, 0.1);
        border-radius: 16px;
    }

    /* Modern Progress Bars */
    .stat-row {
        margin-bottom: 1.2rem;
    }

    .progress-modern {
        height: 8px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 10px;
        overflow: hidden;
        margin-top: 0.5rem;
    }

    .progress-bar-modern {
        background: linear-gradient(90deg, var(--primary-300), #a3ce14);
        height: 100%;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(var(--primary-300-rgb), 0.5);
        transition: width 1s ease-out;
    }

    /* AI Insight Box */
    .ai-insight-box {
        background: linear-gradient(145deg, rgba(255, 255, 255, 0.03) 0%, rgba(255, 255, 255, 0.01) 100%);
        border: 1px solid rgba(var(--primary-300-rgb), 0.2);
        border-radius: 20px;
        position: relative;
        overflow: hidden;
    }

    .ai-insight-box::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, transparent, var(--primary-300), transparent);
        opacity: 0.5;
    }

    /* Custom Toggle Switch */
    .toggle-switch {
        position: relative;
        display: inline-block;
        width: 48px;
        height: 26px;
    }

    .toggle-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(255, 255, 255, 0.1);
        transition: .4s;
        border-radius: 34px;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 18px;
        width: 18px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }

    input:checked + .slider {
        background-color: var(--primary-300);
        border-color: var(--primary-300);
    }

    input:checked + .slider:before {
        transform: translateX(22px);
        background-color: #000;
    }

    /* Metadata Badges */
    .meta-badge {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-size: 0.9rem;
        color: var(--white-300);
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .meta-badge i {
        color: var(--primary-300);
    }
</style>
@endpush
