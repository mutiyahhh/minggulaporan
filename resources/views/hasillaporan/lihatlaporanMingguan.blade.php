@extends('layouts.appAdmin')

@section('content')
<div class="container-fluid">
    <div class="container rounded bg-white mt-4 mb-4">
        <div class="row">
            <div class="container">
                <!-- Header -->
                <h1>Pilih Minggu Laporan untuk: {{ $judul_laporan->nama_judul }} - Bulan: {{ $nama_bulan }}</h1>

                <!-- Cek apakah judul laporan ada -->
                @if($judul_laporan)
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Minggu Laporan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Looping untuk 4 minggu -->
                        @for($i = 1; $i <= 4; $i++)
                            <tr>
                                <td>{{ $i }}</td>
                                <td>Minggu ke-{{ $i }}</td>
                                <td>
                                    @php
                                        // Cek apakah ada laporan untuk minggu ini
                                        $hasReport = $detailLaporan->where('waktu_bulan_laporan_id', $bulan_id)
                                                                   ->where('minggu', $i)
                                                                   ->isNotEmpty();
                                    @endphp
                                    <!-- Tombol aksi -->
                                    @if($hasReport)
                                        <a href="{{ route('detaillaporanMingguan', ['judul_id' => $judulLaporan->id, 'bulan_id' => $bulan_id, 'minggu_id' => $i]) }}" class="btn btn-primary">
                                            Lihat Detail Mingguan
                                        </a>
                                    @else
                                        <button class="btn btn-secondary" disabled>
                                            Belum Ada Laporan
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @endfor
                    </tbody>
                </table>

                @else
                    <p>Tidak ada judul laporan yang tersedia.</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Footer -->
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
