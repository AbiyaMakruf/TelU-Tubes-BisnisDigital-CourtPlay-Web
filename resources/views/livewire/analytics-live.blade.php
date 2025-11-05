<div wire:poll.10s="loadData"> {{-- polling setiap 10 detik --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <span class="badge bg-success">Done: {{ $doneCount }}</span>
            <span class="badge bg-secondary">In Process: {{ $videoInProcessCount }}</span>
        </div>
    </div>

    <table class="table table-dark table-hover align-middle mb-0">
        <thead><tr><th>Name</th><th>Status</th></tr></thead>
        <tbody>
            @foreach($projects as $p)
                <tr>
                    <td>{{ $p->project_name }}</td>
                    <td>
                        @if($p->is_mailed)
                            <span class="badge bg-success">Done</span>
                        @else
                            <span class="badge bg-warning text-dark">Processing</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script>
    document.addEventListener('livewire:navigated', () => {
        toastr.options.positionClass = 'toast-bottom-right';
    });

    Livewire.on('refresh', () => {
        toastr.success('Analytics data updated!');
    });
</script>
