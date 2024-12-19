<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Judul extends Model
{
    use HasFactory;

    protected $table = 'judul_laporan';

    // Kolom-kolom yang dapat diisi
    protected $fillable = ['judul_laporan', 'deskripsi_laporan'];

    // Definisi relasi one-to-many ke SubJudul
    public function subJudul()
    {
        return $this->hasMany(SubJudul::class, 'judul_laporan_id');
    }

    // Definisi relasi one-to-many ke DetailLaporan
    public function details()
    {
        return $this->hasMany(DetailLaporan::class, 'judul_laporan_id');
    }
    
    public function waktuTahun()
    {
        return $this->hasMany(WaktuTahun::class, 'judul_laporan_id');
    }
}
