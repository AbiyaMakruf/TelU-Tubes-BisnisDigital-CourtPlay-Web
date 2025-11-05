@extends('layouts.app')

@section('title', 'Analytics Detail')
@section('fullbleed', true)

@section('content')
    <livewire:analytics-details :projectId="$project->id" />
@endsection

@push('scripts')
<script>
document.addEventListener('livewire:load', () => {
    Livewire.on('showToastr', ({ type, message }) => {
        toastr.options = {
            "progressBar": true,
            "positionClass": "toast-bottom-right",
            "timeOut": 4000
        };
        toastr[type](message);
    });
});
</script>
@endpush
