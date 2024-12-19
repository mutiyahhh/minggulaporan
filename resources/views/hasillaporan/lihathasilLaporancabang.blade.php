@extends('layouts.appAdmin')
<title>Hasil Laporan</title>

@section('content')
    <div class="container-fluid">
        <div class="container rounded bg-white mt-4 mb-4">
            <h3>Hasil Laporan Cabang: {{ $cabangByUser->nama_cabang }}: Judul laporan: {{ $judulLaporan->judul_laporan }} untuk Bulan: {{ $bulanLaporan['waktu_bulan_laporan'] }}</h3>

            <form action="{{ route('approvalMonthly') }}" method="POST" enctype="multipart/form-data">
                @csrf

                @if ($detailLaporan->isNotEmpty())
                    <input type="hidden" name="judul_laporan_id" value="{{ $judulLaporan->id }}">
                    <input type="hidden" name="waktu_bulan_laporan_id" value="{{ $bulanLaporan['id'] }}">
                    <input type="hidden" name="year" value="{{ $year }}" />
                    <input type="hidden" name="cabang_id" value="{{ $cabang_id }}"/>
                    <input type="hidden" name="bulan_id" value="{{ $bulan_id }}"/>
                    <input type="hidden" name="judul_id" value="{{ $judul_id }}"/>
                    <input type="hidden" name="is_monthly" value="{{app('request')->input('is_monthly')}}"/>
                @else
                    <p>Tidak ada detail laporan yang ditemukan.</p>
                @endif

                @foreach ($detailLaporan as $detail)
                    <div>
                        <!-- Menampilkan subjudul laporan -->
                        <h4>Subjudul: {{ $detail->subjudul->subjudul_laporan }}</h4>
                    </div>

                    @if (count($bulanLaporanByDetail) > 0)
                        @foreach ($bulanLaporanByDetail as $bulanLaporan)
                            @if ($bulanLaporan->detail_id == $detail->id )
                                @if ($bulanLaporan->tipe_laporan == "foto")
                                    <div class="mb-3">
                                        <label for="photos_{{ $detail->subjudul->id }}" class="form-label">Foto</label>
                                        <input type="hidden" name="subjudul_laporan_id_{{ $detail->subjudul->id }}" id="subjudul_laporan_id_{{ $detail->subjudul->id }}" value="{{ $detail->subjudul->id }}" />
                                        <img src="{{ url(Storage::url($bulanLaporan->path_storage)) }}" alt="gambar/vidio" width="320">
                                    </div>
                                @elseif($bulanLaporan->tipe_laporan == "video")
                                    <div class="mb-3">
                                        <label for="videos_{{ $detail->subjudul->id }}" class="form-label">Video</label>
                                        <input type="hidden" name="subjudul_laporan_id_{{ $detail->subjudul->id }}" id="subjudul_laporan_id_{{ $detail->subjudul->id }}" value="{{ $detail->subjudul->id }}" />
                                        <input type="text" name="videos[{{ $detail->subjudul->id }}]" class="form-control" id="videos_{{ $detail->subjudul->id }}">
                                    </div>
                                @endif
                            @endif
                        @endforeach
                    @else
                        <p>Tidak ada foto/ video yang tercatat.</p>
                    @endif
                @endforeach

                <!-- Menampilkan catatan laporan yang sesuai dengan subjudul -->
                @if (count($detailLaporan) > 0)
                    <div>
                        <p>Catatan: {{ $detail->catatan_laporan }}</p>
                    </div>
                @else

                @endif
                <hr>
                @if($detailLaporan[0]->status_laporan == "waiting")
                    <div class="mt-3">
                        <label class="form-label">Setujui atau Tolak Laporan Ini:</label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="approval_status" id="approve_example" value="approve">
                            <label class="form-check-label text-success" for="approve_example">
                                <i class="fa-solid fa-thumbs-up"></i> Approve
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="approval_status" id="reject_example" value="reject">
                            <label class="form-check-label text-danger" for="reject_example">
                                <i class="fa-solid fa-thumbs-down"></i> Reject
                            </label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan Laporan</button>
                @else
                    <p>{{ $detailLaporan[0]->status_laporan }}</p>
                    <button type="submit" class="btn btn-primary disabled">Simpan Laporan</button>
                @endif

            </form>
        </div>
    </div>
@endsection
{{-- @extends('layouts.appAdmin')
<title>Masukan Laporan</title>

@section('content')
    <div class="container-fluid">
        <div class="container rounded bg-white mt-4 mb-4">
            <h3>Masukan Laporan {{ $judulLaporan->judul_laporan }} untuk Bulan: {{ $bulanLaporan['waktu_bulan_laporan'] }}</h3>

            <form action="{{ route('storeLaporanWithFiles') }}" method="POST" enctype="multipart/form-data">
                @csrf

                @if ($detailLaporan->isNotEmpty())
                    <input type="hidden" name="judul_laporan_id" value="{{ $judulLaporan->id }}">
                    <input type="hidden" name="waktu_bulan_laporan_id" value="{{ $bulanLaporan['id'] }}">
                    <input type="hidden" name="detail_id" value="{{ $detailLaporan[0]->id }}" />
                    <input type="hidden" name="year" value="{{ $year }}" />
                    <input type="hidden" name="is_monthly" value="{{ app('request')->input('is_monthly') }}" />
                @else
                    <p>Tidak ada detail laporan yang ditemukan.</p>
                @endif

                <!-- Iterasi untuk setiap subjudul laporan -->
                @foreach ($detailLaporan as $detail)
                    <div>
                        <!-- Menampilkan subjudul laporan -->
                        <h4>Subjudul: {{ $detail->subjudul->subjudul_laporan }}</h4>
                    </div>
                    @if (count($bulanLaporanByDetail) > 0)

                        @foreach ($bulanLaporanByDetail as $bulanLaporan)
                            @if ($bulanLaporan->detail_id == $detail->id )
                                @if ($bulanLaporan->tipe_laporan == "foto")
                                    <div class="mb-3">
                                        {{ dd($detail) }}
                                        <label for="photos_{{ $detail->subjudul->id }}" class="form-label">Foto</label>
                                        <input type="hidden" name="subjudul_laporan_id_{{ $detail->subjudul->id }}" id="subjudul_laporan_id_{{ $detail->subjudul->id }}" value="{{ $detail->subjudul->id }}" />
                                        <img src="{{ url(Storage::url($bulanLaporan->path_storage)) }}" alt="gambar/vidio" width="320">
                                    </div>
                                @elseif($bulanLaporan->tipe_laporan == "video")
                                     <div class="mb-3">
                                        <label for="videos_{{ $detail->subjudul->id }}" class="form-label">Video</label>
                                        <input type="hidden" name="subjudul_laporan_id_{{ $detail->subjudul->id }}" id="subjudul_laporan_id_{{ $detail->subjudul->id }}" value="{{ $detail->subjudul->id }}" />
                                        <input type="text" name="videos[{{ $detail->subjudul->id }}]" class="form-control" id="videos_{{ $detail->subjudul->id }}">
                                    </div>
                                @endif
                            @endif
                        @endforeach
                    @else
                        <p>Tidak ada foto/ video yang tercatat.</p>
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
                <!-- Approve and Reject buttons (layout only) -->
            <div class="mt-3">
                <label class="form-label">Setujui atau Tolak Laporan Ini:</label>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="approval_status" id="approve_example" value="approve">
                    <label class="form-check-label text-success" for="approve_example">
                        <i class="fa-solid fa-thumbs-up"></i> Approve
                    </label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="approval_status" id="reject_example" value="reject">
                    <label class="form-check-label text-danger" for="reject_example">
                        <i class="fa-solid fa-thumbs-down"></i> Reject
                    </label>
                </div>
            </div>


                <button type="submit" class="btn btn-primary">Simpan Laporan</button>
            </form>
        </div>
    </div>
@endsection --}}
