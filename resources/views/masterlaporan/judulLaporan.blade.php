@extends('layouts.appAdmin')
<title>Daftar Judul Laporan</title>

@section('content')
<div class="container-fluid">
    <div class="container rounded bg-white mt-4 mb-4">
        <div class="row">
            <div class="container">
                <h>Pilih Judul Laporan</h3>

                <!-- Tabel untuk menampilkan daftar judul laporan -->
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Judul Laporan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($judulLaporan as $index => $judul)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $judul->judul_laporan }}</td>
                                <td>
                                    <a href="{{ route('tahunLaporan', ['id' => $judul->id, 'is_monthly' => app('request')->input('is_monthly') ]) }}" class="btn btn-primary">
                                        Lihat Tahun
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
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
