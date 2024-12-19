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
        Schema::create('detail_report_monthly', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('judul_laporan_id');
            $table->unsignedBigInteger('subjudul_laporan_id');
            $table->unsignedBigInteger('detail_id');
            $table->unsignedInteger('month');
            $table->enum('tipe_laporan', ['foto', 'video'])->default('foto');
            $table->string('path_photo')->nullable(true);
            $table->string('path_video')->nullable(true);
            $table->foreign('judul_laporan_id')->references('id')->on('judul_laporan')->onDelete('cascade');
            $table->foreign('subjudul_laporan_id')->references('id')->on('subjudul_laporan')->onDelete('cascade');
            $table->foreign('detail_id')->references('id')->on('detail_laporan')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_report_monthly');
    }
};
