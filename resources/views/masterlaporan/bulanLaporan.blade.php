@extends('layouts.appAdmin')
<title>Daftar Bulan Laporan</title>

@section('content')
    <div class="container-fluid">
        <div class="container rounded bg-white mt-4 mb-4">
            <div class="row">
                <div class="container">
                    <h3>Pilih Bulan Laporan untuk: {{ $judul_laporan->judul_laporan }} </h3>
                    @if ($judul_laporan)
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Bulan Laporan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($listMonthInput as $index => $object)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $object['name_of_month'] }}</td>
                                        <td>
                                            @if (request()->routeIs('bulanLaporan') && (string) app('request')->input('is_monthly') === '0')
                                                @if ($object['allowed'])
                                                    <a href="{{ route('mingguLaporan', ['year' => $year, 'judul_id' => $judul_laporan->id, 'detail_id' => $detailLaporan->id, 'bulan_id' => $object['month'], 'is_monthly' => app('request')->input('is_monthly')]) }}"
                                                        class="btn btn-primary">
                                                        Lihat Minggu
                                                    </a>
                                                @else
                                                    <a href="{{ route('mingguLaporan', ['year' => $year, 'judul_id' => $judul_laporan->id, 'detail_id' => $detailLaporan->id, 'bulan_id' => $object['month'], 'is_monthly' => app('request')->input('is_monthly')]) }}"
                                                        class="btn btn-primary disabled">
                                                        Lihat Minggu
                                                    </a>
                                                @endif
                                            @elseif(request()->routeIs('bulanLaporan') && (string) app('request')->input('is_monthly') === '1')
                                                @if(auth()->user()->type == "admin" || auth()->user()->type == "manager" )
                                                    <a href="{{ route('masukanLaporanBulanan', ['year' => $year, 'judul_id' => $judul_laporan->id, 'bulan_id' => $object['month'], 'detail_id' => $detailLaporan->id, 'is_monthly' => app('request')->input('is_monthly')]) }}"
                                                       class="btn btn-primary disabled">
                                                        Masukan Laporan Bulanan
                                                    </a>
                                                @else
                                                    @if ($object['allowed'])
                                                        <a href="{{ route('masukanLaporanBulanan', ['year' => $year, 'judul_id' => $judul_laporan->id, 'bulan_id' => $object['month'], 'detail_id' => $detailLaporan->id, 'is_monthly' => app('request')->input('is_monthly')]) }}"
                                                           class="btn btn-primary">
                                                            Masukan Laporan Bulanan
                                                        </a>
                                                    @else
                                                        <a href="{{ route('masukanLaporanBulanan', ['year' => $year, 'judul_id' => $judul_laporan->id, 'bulan_id' => $object['month'], 'detail_id' => $detailLaporan->id, 'is_monthly' => app('request')->input('is_monthly')]) }}"
                                                           class="btn btn-primary disabled">
                                                            Masukan Laporan Bulanan
                                                        </a>
                                                    @endif
                                                @endif
                                            @endif

                                            @if(auth()->user()->type == "admin" || auth()->user()->type == "manager" )
                                                <a href="#" class="btn btn-warning disabled">
                                                    Edit
                                                </a>
                                            @else
                                                @if ($object['allowed'])
                                                    <a href="{{ route('editLaporanBulanan', ['year' => $year, 'judul_id' => $judul_laporan->id, 'bulan_id' => $object['month'], 'detail_id' => $detailLaporan->id, 'is_monthly' => app('request')->input('is_monthly')]) }}"
                                                       class="btn btn-warning">
                                                        Edit
                                                    </a>
                                                @else
                                                    <a href="#" class="btn btn-warning disabled">
                                                        Edit
                                                    </a>
                                                @endif
                                            @endif

                                            @if ($object['allowed'])
                                                <a href="{{ route('showAllMonthlyReport', ['year' => $year, 'month' => $object['month'], 'judul_id' => $judul_laporan->id]) }}"
                                                    class="btn btn-success">
                                                    Lihat Laporan
                                                </a>
                                            @else
                                                <button type="button" class="btn btn-success disabled">Lihat
                                                    Laporan</button>
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
