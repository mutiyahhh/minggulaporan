<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('waktu_bulan_laporan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('waktu_tahun_laporan_id');
            $table->date('waktu_bulan_laporan');
            $table->timestamp('start')->nullable(); // Kolom timestamp untuk start
            $table->timestamp('end')->nullable(); // Kolom timestamp untuk end

            $table->foreign('waktu_tahun_laporan_id')->references('id')->on('waktu_tahun_laporan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('waktu_bulan_laporan');
    }
};
