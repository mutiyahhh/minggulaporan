<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cabang; // Pastikan model Cabang diimpor di sini

class CreateCabangsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Data yang akan dimasukkan ke tabel cabangs
        $cabangs = [
            [
                'nama_cabang' => 'Cabang A',
                'area' => '1',
                'alamat' => 'Jl. Mawar No. 1',
                'nomor_hp' => '08123456789',
            ],
            [
                'nama_cabang' => 'Cabang B',
                'area' => '2',
                'alamat' => 'Jl. Melati No. 2',
                'nomor_hp' => '08198765432',
            ],
        ];

        // Menggunakan metode create untuk menambahkan data ke tabel cabangs
        foreach ($cabangs as $cabang) {
            Cabang::create($cabang);
        }
    }
}


