@extends('layouts.app')

@section('title', 'Analytics Dashboard')
@section('fullbleed', true)

@section('content')
    {{-- Sisipkan komponen Livewire --}}
    <livewire:analytics-dashboard />
@endsection

@push('scripts')
@endpush
