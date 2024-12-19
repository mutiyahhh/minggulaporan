@extends('layouts.appAdmin')
<title>Daftar Tahun Laporan</title>

@section('content')
<div class="container-fluid">
    <div class="container rounded bg-white mt-4 mb-4">
        <div class="row">
            <div class="container">
                <h3>Pilih Tahun Laporan untuk: {{ $judul->judul_laporan }}</h3> <!-- Menggunakan $judul->judul_laporan -->

                <!-- Tabel untuk menampilkan daftar tahun laporan -->
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Tahun Laporan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($detailLaporan as $tahun => $laporan)
                            <tr>
                                <td>{{ $loop->iteration }}</td> <!-- Penomoran otomatis -->
                                <td>{{ $tahun }}</td> <!-- Menampilkan tahun laporan -->
                                <td>
                                    <!-- Tombol untuk melihat bulan laporan -->
                                    <a href="{{ route('bulanLaporan', [
                                        'year' => $tahun, 
                                        'detail_id' => $laporan[0]->id, 
                                        'judul_id' => $judul->id,  // Ganti dari $judul_id menjadi $judul->id
                                        'is_monthly' => app('request')->input('is_monthly')
                                    ]) }}" class="btn btn-primary">
                                        Lihat Bulan
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
</div>
@endsection
