@extends('layouts.appAdmin')

@section('content')
<div class="container-fluid">
    <h1>Detail Laporan Bulanan</h1>

    <div class="row">
        @foreach($bulanLaporan as $laporan)
        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ $laporan->judul_laporan }}</h5>
                    <h6 class="card-subtitle mb-2 text-muted">{{ $laporan->subjudul_laporan }}</h6>
                    <p class="card-text">Catatan: {{ $laporan->catatan_laporan }}</p>
                    <p class="card-text">Start Time: {{ $laporan->start_time }}</p>
                    <p class="card-text">End Time: {{ $laporan->end_time }}</p>
                    <p class="card-text">Status: <span class="badge bg-info">{{ $laporan->status_laporan }}</span></p>
                    <button class="btn btn-success" onclick="approveLaporan({{ $laporan->id }})">Approve</button>
                    <button class="btn btn-danger" onclick="rejectLaporan({{ $laporan->id }})">Reject</button>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<script>
    function approveLaporan(id) {
        if (confirm('Are you sure you want to approve this report?')) {
            fetch(`/laporan/${id}/approve`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Report approved successfully!');
                    location.reload();
                } else {
                    alert('Error approving the report.');
                }
            });
        }
    }

    function rejectLaporan(id) {
        if (confirm('Are you sure you want to reject this report?')) {
            fetch(`/laporan/${id}/reject`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Report rejected successfully!');
                    location.reload();
                } else {
                    alert('Error rejecting the report.');
                }
            });
        }
    }
</script>
@endsection
