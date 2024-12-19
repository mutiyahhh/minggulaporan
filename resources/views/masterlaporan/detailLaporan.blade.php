@extends('layouts.appAdmin')

@section('content')
<div class="container-fluid">
    <h1>Detail Laporan</h1>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Judul Laporan</th>
                <th>Subjudul Laporan</th>
                <th>Catatan</th>
                <th>Jenis Laporan</th>
                <th>Start Time</th>
                <th>End Time </th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            {{-- {{ dd($judulLaporan->details) }} --}}
            @foreach($judulLaporan->details as $detail)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $judulLaporan->judul_laporan }}</td>
                    <td>{{ $detail->subjudul->subjudul_laporan }}</td>
                    <td>{{ $detail->catatan_laporan }}</td>
                    @if($detail->jenis_laporan == "weekly")
                        <td>Mingguan</td>
                    @else
                        <td>Bulanan</td>
                    @endif
                    <td>{{ $detail->start_time }}</td>
                    <td>{{ $detail->end_time }}</td>
                    <td>{{ $detail->status_laporan }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
