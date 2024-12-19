@extends('layouts.appAdmin')
<title>Daftar Cabang</title>

@section('content')
<div class="container-fluid">
    @if(Session::has('message'))
        <!-- Flexbox container for aligning the toasts -->
        <div class="alert alert-warning alert-dismissible fade show mt-3" role="alert">
            <strong>Upppss!</strong> {{ Session::get('message') }}.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
        @if(Session::has('status'))
            <!-- Flexbox container for aligning the toasts -->
            <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                <strong>Sukses!</strong> {{ Session::get('status') }}.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
    <div class="container rounded bg-white mt-4 mb-4">
        <div class="row">
            <div class="container">
                <h3>Daftar Cabang</h3>
                <h3>Pilih 3 Bulan Laporan untuk: {{ $judul_laporan->judul_laporan }}</h3> <!-- Display judul_laporan title -->

                <form action="{{ route('listCabang', ['year' => $year,'bulan_id' => $bulan_id,'judul_id' => $judul_laporan->id ]) }}" method="GET">                    <div class="input-group mb-3">
                        <input type="text" name="search" class="form-control" placeholder="Cari..." value="{{ $search ?? '' }}">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="submit">Cari</button>
                        </div>
                    </div>
                </form>
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama Cabang</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cabangs as $cabang)
                        <tr>
                            <td>{{ $cabang->id }}</td>
                            <td>{{ $cabang->nama_cabang }}</td>
                            <td>
                                <a href="{{ route('cabangs.show', $cabang->id) }}" class="btn btn-primary">Detail cabang</a>
                                <a href="{{ route('getApprovalMonthly',
                                            [
                                                'judul_laporan_id' => $judul_laporan->id,
                                                'bulan_id' => $bulan_id,
                                                'year' => $year,
                                                'cabang_id' => $cabang->id,
                                                'is_monthly' => app('request')->input('is_monthly')
                                             ]
                                           )
                                         }}" class="btn btn-primary">Lihat Laporan</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <!-- Pagination Links -->
                {{ $cabangs->withQueryString()->links() }}
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
