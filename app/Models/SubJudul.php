<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubJudul extends Model
{
    use HasFactory;

    protected $table = 'subjudul_laporan';

    protected $fillable = [
        'judul_laporan_id',
        'subjudul_laporan',
        'tipe_laporan',
        'deskripsi',
        'is_wajib',
    ];

    public function judul()
    {
        return $this->belongsTo(Judul::class, 'judul_laporan_id');
    }

    public function detailLaporan()
    {
        return $this->hasMany(DetailLaporan::class, 'subjudul_laporan_id');
    }
}
