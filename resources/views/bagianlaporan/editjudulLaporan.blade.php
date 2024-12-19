@extends('layouts.appAdmin')

@section('content')
<div class="container-fluid">        
    <div class="container rounded bg-white mt-4 mb-4">
        <div class="row">
            <div class="container">
            <h3>Edit Judul Laporan: {{ $judul->judul_laporan }}</h3>
            <form action="{{ route('updateJudul', $judul->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="judul_laporan" class="form-label">Judul Laporan</label>
                        <input type="text" class="form-control" id="judul_laporan" name="judul_laporan" value="{{ $judul->judul_laporan }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="deskripsi_laporan" class="form-label">Deskripsi Laporan</label>
                        <textarea class="form-control" id="deskripsi_laporan" name="deskripsi_laporan" rows="4" required>{{ $judul->deskripsi_laporan }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('judulLaporan') }}" class="btn btn-secondary">Kembali</a>
                </form>
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
                        <a class="text-red">
                        1.0.0
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</footer>
@endsection
