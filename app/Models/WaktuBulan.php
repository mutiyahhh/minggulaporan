<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WaktuBulan extends Model
{
    use HasFactory;

    protected $table = 'waktu_bulan_laporan';

    protected $fillable = [
        'waktu_tahun_laporan_id',
        'waktu_bulan_laporan',
        'start',
        'end',
    ];

    public function waktuTahunLaporan()
    {
        return $this->belongsTo(WaktuTahun::class, 'waktu_tahun_laporan_id');
    }

    public function detailLaporans()
    {
        return $this->hasMany(DetailLaporan::class, 'waktu_bulan_laporan_id');
    }

    public function waktuTahun()
    {
        return $this->belongsTo(WaktuTahun::class, 'waktu_tahun_laporan_id');
    }

}
