@extends('layouts.appAdmin')
<title>Masukan Laporan</title>
@section('content')
    <div class="container-fluid">
        <div class="container rounded bg-white mt-4 mb-4">
            <h3>Judul Laporan: {{ $judulLaporan->judul_laporan }} untuk Bulan: {{ $bulanLaporan['waktu_bulan_laporan'] }}: {{ $weekReport['waktu_minggu_laporan'] }}</h3>

            <form action="{{ route('createWeeklyReport') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @if ($detailLaporan->isNotEmpty())
                    <input type="hidden" name="judul_laporan_id" value="{{ $judulLaporan->id }}">
                    <input type="hidden" name="waktu_minggu_laporan_id" value="{{ $weekReport['id'] }}">
                    <input type="hidden" name="waktu_bulanan_laporan_id" value="{{ $bulanLaporan['id'] }}">
                    <input type="hidden" name="year" value="{{ $year }}" />
                    <input type="hidden" name="is_monthly" value="{{ app('request')->input('is_monthly') }}" />
                    <input type="hidden" name="month" value="{{$bulanLaporan['id']}}">
                @else
                    <p>Tidak ada detail laporan yang ditemukan.</p>
                @endif

                <!-- Iterasi untuk setiap subjudul laporan -->
                @foreach ($detailLaporan as $detail)
                    <div>
                        <!-- Menampilkan subjudul laporan -->
                        <h4>Subjudul: {{ $detail->subjudul->subjudul_laporan }}</h4>
                    </div>
                    @if($detail->subjudul->tipe_laporan == "foto")
                        <!-- Form upload foto untuk setiap subjudul -->
                        <div class="mb-3">
                            <label for="photos_{{ $detail->subjudul->id }}" class="form-label">Upload Foto</label>
                            <input type="hidden" name="subjudul_laporan_id_{{ $detail->subjudul->id }}" id="subjudul_laporan_id_{{ $detail->subjudul->id }}" value="{{ $detail->subjudul->id }}" />
                            <input type="hidden" name="detail_id[{{ $detail->subjudul->id }}]" value="{{ $detail->id }}"  />
                            <input type="file" name="photos[{{ $detail->subjudul->id }}]" class="form-control" id="photos_{{ $detail->subjudul->id }}">
                        </div>
                    @elseif($detail->subjudul->tipe_laporan == "video")
                        <!-- Form upload foto untuk setiap subjudul -->
                        <div class="mb-3">
                            <label for="videos_{{ $detail->subjudul->id }}" class="form-label">Upload Link Video</label>
                            <input type="hidden" name="detail_id[{{ $detail->subjudul->id }}]" value="{{ $detail->id }}"  />
                            <input type="hidden" name="subjudul_laporan_id_{{ $detail->subjudul->id }}" id="subjudul_laporan_id_{{ $detail->subjudul->id }}" value="{{ $detail->subjudul->id }}" />
                            <input type="text" name="videos[{{ $detail->subjudul->id }}]" class="form-control" id="videos_{{ $detail->subjudul->id }}">
                        </div>
                    @endif
                    <!-- Menampilkan catatan laporan yang sesuai dengan subjudul -->
                    @if (count($detailLaporan) > 0)
                        <div>
                            <p>Catatan: {{ $detail->catatan_laporan }}</p>
                        </div>
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
