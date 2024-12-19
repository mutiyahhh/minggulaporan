@extends('layouts.appAdmin')

@section('content')
<div class="container-fluid">
    <div class="container rounded bg-white mt-4 mb-4">
        <div class="row">
            <div class="container">
            <h3>Edit Item Laporan: {{ $subjudul->subjudul_laporan }}</h3>
                
                <form action="{{ route('subjudul.update', $subjudul->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="subjudul_laporan" class="form-label">Subjudul Laporan</label>
                        <input type="text" class="form-control" id="subjudul_laporan" name="subjudul_laporan" value="{{ $subjudul->subjudul_laporan }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="tipe_laporan" class="form-label">Tipe Laporan</label>
                        <select class="form-control" id="tipe_laporan" name="tipe_laporan" required>
                            <option value="foto" {{ $subjudul->tipe_laporan == 'foto' ? 'selected' : '' }}>Foto</option>
                            <option value="video" {{ $subjudul->tipe_laporan == 'video' ? 'selected' : '' }}>Video</option>
                            {{-- <option value="text" {{ $subjudul->tipe_laporan == 'text' ? 'selected' : '' }}>Text</option>
                            <option value="file_lainya" {{ $subjudul->tipe_laporan == 'file_lainya' ? 'selected' : '' }}>File Lainya</option> --}}
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi">{{ $subjudul->deskripsi }}</textarea>
                    </div>
                    <!-- Menambahkan kolom is_wajib -->
                    <div class="mb-3">
                        <label for="is_wajib" class="form-label">Status Wajib</label>
                        <select class="form-control" id="is_wajib" name="is_wajib" required>
                            <option value="1" {{ $subjudul->is_wajib == 1 ? 'selected' : '' }}>Wajib</option>
                            <option value="0" {{ $subjudul->is_wajib == 0 ? 'selected' : '' }}>Tidak Wajib</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
                <a href="{{ route('subjudul.laporan', $subjudul->judul_laporan_id) }}" class="btn btn-secondary mt-3">Kembali</a>
            </div>
        </div>
    </div>
</div>
@endsection
