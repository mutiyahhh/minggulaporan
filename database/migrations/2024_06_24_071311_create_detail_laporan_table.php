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
        Schema::create('detail_laporan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('judul_laporan_id');
            $table->unsignedBigInteger('subjudul_laporan_id');
            $table->date('start_time')->nullable(false);
            $table->date('end_time')->nullable(false);
            $table->string('catatan_laporan')->nullable();
            $table->enum('jenis_laporan', ['weekly', 'monthly'])->nullable(true);
            $table->string('order_of_the_week', 50)->nullable(true);
            $table->unsignedBigInteger('waktu_tahun_laporan_id');

            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('judul_laporan_id')->references('id')->on('judul_laporan')->onDelete('cascade');
            $table->foreign('subjudul_laporan_id')->references('id')->on('subjudul_laporan')->onDelete('cascade');
            $table->foreign('waktu_tahun_laporan_id')->references('id')->on('waktu_tahun_laporan')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_laporan');
    }
};
