@extends('layouts.appAdmin')
<title>Masukan Laporan</title>

@section('content')
<div class="container-fluid">
    <div class="container rounded bg-white mt-4 mb-4">
        <h1>Masukan Laporan untuk Bulan: {{ $bulanLaporan->waktu_bulan_laporan }}</h1>
        {{-- {{ dd($subjudulLaporan) }}; --}}
        <form action="{{ route('storeLaporanWithFiles') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            @if ($detailLaporan->isNotEmpty())
                <input type="hidden" name="judul_laporan_id" value="{{ $judulLaporan->id }}">
                <input type="hidden" name="waktu_bulan_laporan_id" value="{{ $bulanLaporan->id }}">
            @else
                <p>Tidak ada detail laporan yang ditemukan.</p>
            @endif
            
            <!-- Iterasi untuk setiap subjudul laporan -->
            @foreach ($subjudulLaporan as $subjudul)
                <div>
                    <!-- Menampilkan subjudul laporan -->
                    <h4>Subjudul: {{ $subjudul->subjudul_laporan }}</h4>
                </div>

                <!-- Form upload foto untuk setiap subjudul -->
                <div class="mb-3">
                    <label for="photos_{{ $subjudul->id }}" class="form-label">Upload Foto</label>
                    <input type="file" name="photos[{{ $subjudul->id }}]" class="form-control" id="photos_{{ $subjudul->id }}">
                </div>

                <!-- Menampilkan catatan laporan yang sesuai dengan subjudul -->
                @php
                    // Filter detail laporan berdasarkan subjudul_id yang sesuai
                    $catatanTerkait = $detailLaporan->where('subjudul_laporan_id', $subjudul->id);
                @endphp

                @if ($catatanTerkait->isNotEmpty())
                    @foreach ($catatanTerkait as $detail)
                        <div>
                            <p>Catatan: {{ $detail->catatan_laporan }}</p>
                        </div>
                    @endforeach
                @else
                    <p>Tidak ada catatan untuk subjudul ini.</p>
                @endif

                <hr>
            @endforeach

            <button type="submit" class="btn btn-primary">Simpan Laporan</button>
        </form>
    </div>
</div>
@endsection








{{-- @extends('layouts.appAdmin')
<title>Masukan Laporan</title>

@section('content')
<div class="container-fluid">
    <div class="container rounded bg-white mt-4 mb-4">
        <h1>Masukan Laporan untuk Bulan: {{ $bulanLaporan->waktu_bulan_laporan }}</h1>

        <form action="{{ route('storeLaporanWithFiles') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <input type="show" name="judul_laporan_id" value="{{ $detailLaporan->first()->judul_laporan_id }}">
            <input type="hidden" name="waktu_bulan_laporan_id" value="{{ $bulanLaporan->id }}">

            <!-- Iterasi melalui setiap subjudul laporan -->
            @foreach ($subjudulLaporan as $subjudul)
                <div>
                    <h4>subjudul: {{ $subjudul->subjudul_laporan }}</h4>
                </div>
                 <!-- Field untuk upload foto berdasarkan detail laporan -->
                <div class="mb-3">
                    <label for="photos_{{ $subjudul->id }}" class="form-label">Upload Foto</label>
                    <input type="file" name="photos[{{ $subjudul->id }}]" class="form-control" id="photos_{{ $subjudul->id }}">
                </div>

                <!-- Iterasi melalui detail laporan yang terkait dengan subjudul -->
                @foreach ($detailLaporan->where('subjudul_laporan_id', $subjudul->id) as $detail)
                    <div>
                        <p>{{ $detail->catatan_laporan }}</p>
                        <!-- Tampilkan informasi lainnya sesuai kebutuhan -->
                    </div>
                @endforeach
            @endforeach

            <button type="submit" class="btn btn-primary">Simpan Laporan</button>
        </form>
    </div>
</div>
@endsection --}}
