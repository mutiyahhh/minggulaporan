@extends('layouts.appAdmin')
<title>Hasil Laporan</title>

@section('content')
    <div class="container-fluid">
        <div class="container rounded bg-white mt-4 mb-4">
            <h3>Hasil Laporan Cabang: {{ $cabangByUser->nama_cabang }}: Judul laporan: {{ $detailLaporan[0]->judul->judul_laporan }} untuk Bulan: {{ $bulanLaporan['waktu_bulan_laporan'] }}</h3>

<form action="{{ route('approvalMonthly') }}" method="POST" enctype="multipart/form-data">
    @csrf

    @if ($bulanLaporanByDetail->isNotEmpty())
        <input type="hidden" name="judul_laporan_id" value="{{ $detailLaporan[0]->judul->id  }}">
        <input type="hidden" name="waktu_bulan_laporan_id" value="{{ $bulanLaporan['id'] }}">
        <input type="hidden" name="year" value="{{ $year }}" />
        <input type="hidden" name="cabang_id" value="{{ $cabang_id }}"/>
        <input type="hidden" name="bulan_id" value="{{ $bulan_id }}"/>
        <input type="hidden" name="judul_id" value="{{ $detailLaporan[0]->judul->id }}"/>
        <input type="hidden" name="list_detail_id" value="{{ $listDetailId }}"/>
        <input type="hidden" name="created_by" value="{{ app('request')->input('user_id_detail') }}"/>
        <input type="hidden" name="is_monthly" value="{{ app('request')->input('is_monthly') }}"/>

        @if (count($bulanLaporanByDetail) > 0)
            @foreach ($bulanLaporanByDetail as $bulanLaporan)
                @if ($bulanLaporan->tipe_laporan == "foto")
                    <div class="mb-3">
                        <label for="photos_{{ $bulanLaporan->subjudul_laporan_id }}" class="form-label">Foto</label>
                        <img src="{{ url(Storage::url($bulanLaporan->path_storage)) }}" alt="gambar/vidio" width="320">
                    </div>
                @elseif($bulanLaporan->tipe_laporan == "video")
                    <div class="mb-3">
                        <label for="videos_{{ $bulanLaporan->subjudul_laporan_id }}" class="form-label">Video</label>
                @if (!empty($bulanLaporan->path_storage))
                            <!-- Menampilkan link jika ada -->
                            <p>
                                <a href="{{ $bulanLaporan->path_storage }}" target="_blank" class="text-primary">
                                    {{ $bulanLaporan->path_storage }}
                                </a>
                            </p>
                        @else
                            <!-- Input untuk link video baru -->
                            <input type="text" name="videos[{{ $bulanLaporan->subjudul_laporan_id }}]" class="form-control" id="videos_{{ $bulanLaporan->subjudul_laporan_id }}" placeholder="Masukkan link YouTube">
                        @endif
                    </div>
                @endif
            @endforeach
        @else
            <p>Tidak ada foto/ video yang tercatat.</p>
        @endif

        @if ($bulanLaporanByDetail[0]->status == "pending" || $bulanLaporanByDetail[0]->status == "waiting")
            @if(auth()->user()->type == "admin" || auth()->user()->type == "manager" )
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
                <p>Menunggu Approval Admin atau Manager</p>
            @endif
        @else
            <p>
                @if ($bulanLaporanByDetail[0]->status == "approve")
                    <i class="fa-solid fa-thumbs-up text-success"></i> Laporan sudah disetujui oleh {{ $bulanLaporanByDetail[0]->approved_by ?? 'N/A' }}
                @elseif ($bulanLaporanByDetail[0]->status == "reject")
                    <i class="fa-solid fa-thumbs-down text-danger"></i> Laporan ditolak oleh {{ $bulanLaporanByDetail[0]->rejected_by ?? 'N/A' }}
                @else
                    Status tidak diketahui.
                @endif
            </p>
        @endif

    @else
        <p>Tidak ada detail laporan yang ditemukan.</p>
    @endif
</form>

        </div>
    </div>
@endsection
