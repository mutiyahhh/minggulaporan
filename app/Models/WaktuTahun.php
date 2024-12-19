<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WaktuTahun extends Model
{
    use HasFactory;

    protected $table = 'waktu_tahun_laporan'; // Nama tabel yang digunakan

    protected $fillable = [
        'waktu_tahun_laporan',
        'catatan'
    ];

    protected $dates = ['waktu_tahun_laporan']; // Specify that this is a date field


        public function judulLaporan()
    {
        return $this->belongsTo(Judul::class, 'judul_laporan_id');
    }
  

}
