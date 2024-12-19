@extends('layouts.appAdmin')
<title>Daftar Judul Laporan</title>

@section('content')
<div class="container-fluid">
    <div class="container rounded bg-white mt-4 mb-4">
        <div class="row">
            <div class="container">
                <h3>Daftar Judul Laporan</h3>
                <a href="{{ route('tambahJudul') }}" class="btn btn-secondary">Tambah Judul Laporan</a>
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Judul Laporan</th>
                            <th>Deskripsi Laporan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($judul_laporan as $judul)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $judul->judul_laporan }}</td>
                            <td>{{ $judul->deskripsi_laporan }}</td>
                            <td>
                                <a href="{{ route('mainLaporan', ['id' => $judul->id]) }}" class="btn btn-primary">Rancang</a>
                                <a href="{{ route('catatanLaporan', ['id' => $judul->id]) }}" class="btn btn-info">Detail</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4">No data available</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                {{ $judul_laporan->links() }}
            </div>
        </div>
    </div>
</div>

<footer class="footer">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6 order-last order-md-first">
                <div class="copyright text-md-start">
                    <p class="text-sm">
                        Developed by
                        <a href="https://www.wahanaritelindo.com/" rel="nofollow" target="_blank" class="text-red">
                            Wahana Ritelindo
                        </a>
                    </p>
                </div>
            </div>
            <div class="col-md-6 order-last order-md-first">
                <div class="copyright text-md-end">
                    <p class="text-sm">
                        Version
                        <a class="text-red">1.0.0</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</footer>
@endsection
