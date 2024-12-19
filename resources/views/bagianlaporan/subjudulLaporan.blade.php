@extends('layouts.appAdmin')
<title>Daftar Subjudul Laporan</title>

@section('content')
<div class="container-fluid">
    <div class="container rounded bg-white mt-4 mb-4">
        <div class="row">
            <div class="container">
            <h3>Daftar item Laporan untuk: {{ $judul_laporan->judul_laporan }}</h3>
            <a href="{{ route('subjudul.create', $judul_laporan->id) }}" class="btn btn-secondary">Tambah Subjudul Laporan</a>
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Item Laporan</th>
                            <th>Tipe Laporan</th>
                            <th>Deskripsi</th>
                            <th>Status Wajib</th> <!-- Tambahkan kolom Status Wajib -->
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($subjudul_laporan as $subjudul)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $subjudul->subjudul_laporan }}</td>
                            <td>{{ $subjudul->tipe_laporan }}</td>
                            <td>{{ $subjudul->deskripsi }}</td>
                            <td>
                                <!-- Menampilkan status wajib atau tidak -->
                                @if($subjudul->is_wajib)
                                    Wajib
                                @else
                                    Tidak Wajib
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('subjudul.edit', ['id' => $subjudul->id]) }}" class="btn btn-warning">Edit</a>
                                <form action="{{ route('subjudul.destroy', ['id' => $subjudul->id]) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus subjudul ini?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6">No data available</td> <!-- Sesuaikan kolom menjadi 6 -->
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                {{ $subjudul_laporan->links() }}
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
