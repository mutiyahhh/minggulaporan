@extends('layouts.appAdmin')
<title>Daftar Bulan Laporan</title>

@section('content')
<div class="container-fluid">
    <div class="container rounded bg-white mt-4 mb-4">
        <div class="row">
            <div class="container">
                <h1>Pilih Bulan Laporan untuk: {{ $judul_laporan->nama_judul }}</h1> <!-- Ganti nama_judul_laporan dengan nama_judul sesuai dengan kolom di model Judul -->

                <!-- Cek apakah judul laporan ada -->
                @if($judul_laporan)
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Bulan Laporan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bulanLaporan as $bulan_id => $bulan)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $bulan }}</td>
                                <td>
                                    @php
                                        // Cek apakah ada laporan untuk bulan ini
                                        $hasReport = $detailLaporan->where('waktu_bulan_laporan_id', $bulan_id)->isNotEmpty();
                                    @endphp
                                    <a href="{{ route('masukanLaporan', ['judul_id' => $judul_laporan->id, 'bulan_id' => $bulan_id, 'minggu_id' => $i]) }}" class="btn btn-primary">
                                        Masukan Laporan
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                @else
                    <p>Tidak ada judul laporan yang tersedia.</p>
                @endif
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
