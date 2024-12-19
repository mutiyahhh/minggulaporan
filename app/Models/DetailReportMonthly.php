<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailReportMonthly extends Model
{
    use HasFactory;

    // Nama tabel yang digunakan oleh model ini
    protected $table = 'detail_report_monthly';

    // Kolom yang dapat diisi massal
    protected $fillable = [
        'judul_laporan_id',
        'subjudul_laporan_id',
        'detail_id',
        'month',
        'tipe_laporan',
        'path_photo',
        'path_video',
    ];
}
