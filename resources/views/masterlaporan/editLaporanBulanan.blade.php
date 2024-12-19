@extends('layouts.appAdmin')
<title>Edit Laporan Bulanan</title>

@section('content')
    <div class="container-fluid">
        <div class="container rounded bg-white mt-4 mb-4">
            <h3>Edit Laporan {{ $judulLaporan->judul_laporan }} untuk Bulan: {{ $bulanLaporan['waktu_bulan_laporan'] }}</h3>


            <form action="{{ route('updateLaporanBulanan', ['year' => $year, 'judul_id' => $judulLaporan->id, 'bulan_id' => $bulanLaporan['id'], 'detail_id' => $detailLaporan->first()->id]) }}" method="POST" enctype="multipart/form-data">
                {{-- <form action="{{ route('updateLaporanBulanan', ['year' => $year, 'judul_id' => $judulLaporan->id, 'bulan_id' => $bulanLaporan->id, 'detail_id' => $detailLaporan->id]) }}" method="POST" enctype="multipart/form-data"> --}}
                @csrf
                <input type="hidden" value="{{ app('request')->input('is_monthly') }}" name="is_monthly" />
                @foreach ($detailLaporan as $detail)
                    <div>
                        <h4>Subjudul: {{ $detail->subjudul->subjudul_laporan }}</h4>
                    </div>
                    
                    @if($detail->subjudul->tipe_laporan == "foto")
                        <div class="mb-3">
                            <label for="photos_{{ $detail->subjudul->id }}" class="form-label">Upload Foto (Opsional)</label>
                            <input type="hidden" name="subjudul_laporan_id_{{ $detail->subjudul->id }}" value="{{ $detail->subjudul->id }}" />
                            <input type="hidden" name="detail_id[{{ $detail->subjudul->id }}]" value="{{ $detail->id }}" />
                            <input type="file" name="photos[{{ $detail->subjudul->id }}]" class="form-control" id="photos_{{ $detail->subjudul->id }}">
                            <p>Foto saat ini: <a href="{{ Storage::url($detail->path_photo) }}" target="_blank">Lihat Foto</a></p>
                                {{-- @if(Storage::exists($detail->path_photo))
                                    <a href="{{ Storage::url($detail->path_photo) }}" target="_blank">Lihat Foto</a>
                                @else
                                    <p>Foto tidak tersedia</p>
                                @endif --}}
                        </div>
                        
                    @elseif($detail->subjudul->tipe_laporan == "video")
                        <div class="mb-3">
                            <label for="videos_{{ $detail->subjudul->id }}" class="form-label">Link Video</label>
                            <input type="hidden" name="subjudul_laporan_id_{{ $detail->subjudul->id }}" value="{{ $detail->subjudul->id }}" />
                            <input type="hidden" name="detail_id[{{ $detail->subjudul->id }}]" value="{{ $detail->id }}" />
                            <input type="text" name="videos[{{ $detail->subjudul->id }}]" class="form-control" id="videos_{{ $detail->subjudul->id }}" value="{{ $detail->video_link }}">
                        </div>
                    @endif

                    <div>
                        <label for="catatan_{{ $detail->subjudul->id }}" class="form-label">Catatan</label>
                        <textarea name="catatan[{{ $detail->subjudul->id }}]" class="form-control" id="catatan_{{ $detail->subjudul->id }}" rows="3">{{ $detail->catatan_laporan }}</textarea>
                    </div>

                    <hr>
                @endforeach

                <button type="submit" class="btn btn-success">Update Laporan</button>
            </form>
        </div>
    </div>
@endsection
