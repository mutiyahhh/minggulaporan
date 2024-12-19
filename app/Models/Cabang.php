<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Casts\Attribute;



class Cabang extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'nama_cabang',
        'area',
        'alamat',
        'nomor_hp',
    ];

    protected $table = 'cabangs';

    public function users()
    {
        return $this->hasMany(User::class, 'cabang_id');
    }
}

