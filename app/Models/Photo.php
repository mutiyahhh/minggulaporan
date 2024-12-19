<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{



    protected $fillable = ['laporan_id','name', 'path'];

    // Jika Anda membutuhkan timestamp yang diperlukan oleh Eloquent (created_at dan updated_at),
    // Anda tidak perlu menambahkan kode tambahan di model karena Eloquent secara default
    // mengasumsikan bahwa tabel memiliki kolom timestamp.
}