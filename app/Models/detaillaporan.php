<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailLaporan extends Model
{
    use HasFactory;

    // Nama tabel yang digunakan oleh model ini
    protected $table = 'detail_laporan';

    // Kolom yang dapat diisi massal
    protected $fillable = [
        'created_by',
        'judul_laporan_id',
        'subjudul_laporan_id',
        'start_time',
        'end_time',
        'waktu_tahun_laporan_id',
        'catatan_laporan',
        'jenis_laporan',
        'order_of_the_week',
    ];

    // Definisi relasi
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function judul()
    {
        return $this->belongsTo(Judul::class, 'judul_laporan_id');
    }

    public function subjudul()
    {
        return $this->belongsTo(SubJudul::class, 'subjudul_laporan_id');
    }

    public function waktuTahun(){
         return $this->belongsTo(waktuTahun::class, 'waktu_tahun_laporan_id');
    }

    public function waktuBulan()
    {
        return $this->belongsTo(WaktuBulan::class, 'waktu_bulan_laporan_id');
    }

}
