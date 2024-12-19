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
        Schema::create('subjudul_laporan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('judul_laporan_id');
            $table->string('subjudul_laporan');
            $table->enum('tipe_laporan', ['foto', 'video', 'text', 'file_lainya'])->default('file_lainya');
            $table->string('deskripsi')->nullable();
            $table->foreign('judul_laporan_id')->references('id')->on('judul_laporan')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subjudul_laporan');
    }
};
