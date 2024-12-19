@extends('layouts.appAdmin')
<title>Daftar Laporan</title>

@section('content')
    <div class="container-fluid">
        <div class="container rounded bg-white mt-4 mb-4">
            <div class="row">
                <div class="container">

                    <h3>List Laporan untuk: {{ $judulLaporan->judul_laporan }} Tahun: {{ $year }} Bulan:
                        {{ $monthName }}</h3>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama User</th>
                                <th>Cabang</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($laporans as $index => $laporan)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $laporan->user->name }}</td>
                                    <td>{{ $laporan->user->Cabang->nama_cabang }}</td>
                                    <td>
                                        @if ($laporan->status == 'pending' || $laporan->status == 'waiting')
                                            <span class="badge bg-warning">
                                                <i class="fas fa-clock"></i> Menunggu Persetujuan
                                            </span>
                                        @elseif ($laporan->status == 'approve')
                                            <span class="badge bg-success">
                                                <i class="fas fa-check"></i> Approve
                                            </span>
                                        @else
                                            <span class="badge bg-danger">
                                                <i class="fas fa-times"></i> Reject
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('getApprovalMonthly',
                                                      [
                                                          'year' => $year,
                                                          'bulan_id' => $month,
                                                          'user_id_detail' => $laporan->user->id,
                                                          'judul_laporan_id' => $judulLaporan->id
                                                      ])
                                                 }}" class="btn btn-primary">Lihat Laporan</a>
                                        {{-- <form action="{{ route('deleteLaporan', $laporan->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </form> --}}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Tidak ada laporan yang tersedia.</td>
                                </tr>
                            @endforelse
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
