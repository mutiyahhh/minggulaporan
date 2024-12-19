@extends('layouts.appAdmin') 
<title>Daftar Minggu Laporan</title>

@section('content')
<div class="container-fluid">
    <div class="container rounded bg-white mt-4 mb-4">
        <div class="row">
            <div class="container">
                <h1>Pilih Minggu Laporan untuk: {{ $judulLaporan->judul_laporan }} - Bulan Ke: {{ $bulanLaporan['waktu_bulan_laporan'] }} </h1>

                @if($judulLaporan)
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Minggu Laporan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($listWeeklyReporting as $index => $object)
                            <tr>
                                <td>{{ $index+1 }}</td>
                                <td>{{ $object['name_of_week'] }}</td>
                                <td>
                                    {{-- Button Masukan Laporan --}}
                                @if($object['allowed'])
                                    <a href="{{ route('getReportWeekly', [
                                            'year'=> $year,
                                            'judul_id' => $judulLaporan->id,
                                            'bulan_id' => $bulanLaporan['id'],
                                            'minggu_id' => $object['week'],
                                            'is_monthly' => app('request')->input('is_monthly')
                                        ]) }}" class="btn btn-primary">
                                        Masukan Laporan Mingguan
                                    </a>
                                @else
                                    <a href="#" class="btn btn-primary disabled">Masukan Laporan Mingguan</a>
                                @endif

                                {{-- Button Edit Laporan --}}
                                @if(auth()->user()->type == "admin" || auth()->user()->type == "manager")
                                    <a href="#" class="btn btn-warning disabled">Edit</a>
                                @else
                                    @if($object['allowed'])
                                        <a href="{{ route('editLaporanMingguan', [
                                                'year'=> $year,
                                                'judul_id' => $judulLaporan->id,
                                                'bulan_id' => $bulanLaporan['id'],
                                                'minggu_id' => $object['week']
                                            ]) }}" class="btn btn-warning">
                                            Edit
                                        </a>
                                    @else
                                        <a href="#" class="btn btn-warning disabled">Edit</a>
                                    @endif
                                @endif

                                {{-- Button Lihat Laporan --}}
                                @if($object['allowed'])
                                    <a href="{{ route('showWeeklyReports', [
                                            'year'=> $year,
                                            'judul_id' => $judulLaporan->id,
                                            'bulan_id' => $bulanLaporan['id'],
                                            'minggu_id' => $object['week']
                                        ]) }}" class="btn btn-success">
                                        Lihat Laporan
                                    </a>
                                @else
                                    <button type="button" class="btn btn-success disabled">Lihat Laporan</button>
                                @endif
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
