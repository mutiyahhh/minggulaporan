@extends('layouts.appAdmin')

@section('content')
<div class="container-fluid">
    <div class="container rounded bg-white mt-4 mb-4">
        <div class="row">
            <div class="container">
                <h1>Tambah kolom upload untuk "{{ $judul_laporan->judul_laporan }}"</h1>
                <form action="{{ route('subjudul.store', $judul_laporan->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="subjudul_laporan" class="form-label">Upload Laporan</label>
                        <input type="text" class="form-control" id="subjudul_laporan" name="subjudul_laporan" required>
                    </div>
                    <div class="mb-3">
                        <label for="tipe_laporan" class="form-label">Tipe Laporan</label>
                        <select class="form-control" id="tipe_laporan" name="tipe_laporan" required>
                            <option value="foto">Foto</option>
                            <option value="video">Video</option>
                            {{-- <option value="text">Teks</option>
                            <option value="file_lainya">File Lainnya</option> --}}
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="is_wajib" class="form-label">Apakah laporan ini wajib diupload?</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="is_wajib" id="wajib" value="1" required>
                            <label class="form-check-label" for="wajib">Wajib</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="is_wajib" id="tidak_wajib" value="0">
                            <label class="form-check-label" for="tidak_wajib">Tidak Wajib</label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
                <a href="{{ route('subjudul.laporan', $judul_laporan->id) }}" class="btn btn-secondary mt-3">Kembali</a>
            </div>
        </div>
    </div>
</div>
@endsection
