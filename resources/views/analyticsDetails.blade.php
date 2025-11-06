@extends('layouts.app')

@section('title', 'Analytics Detail')
@section('fullbleed', true)

@section('content')
    <livewire:analytics-details :projectId="$project->id" />
@endsection

@push('scripts')
@endpush
