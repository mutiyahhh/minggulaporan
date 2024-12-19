<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MingguanLaporan extends Model
{
    use HasFactory;
    protected $table = 'mingguan_laporan';

    protected $fillable = [
        'detail_id',
        'judul_laporan_id',
        'subjudul_laporan_id',
        'month',
        'week',
        'tipe_laporan',
        'path_storage',
        'created_by',
        'approved_by',
        'rejected_by',
        'year'
    ];

    public function judul()
    {
        return $this->belongsTo(Judul::class, 'judul_laporan_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
