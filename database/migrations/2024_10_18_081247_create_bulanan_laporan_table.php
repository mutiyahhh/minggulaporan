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
        Schema::create('bulanan_laporan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('detail_id');
            $table->unsignedBigInteger('judul_laporan_id');
            $table->unsignedBigInteger('subjudul_laporan_id');
            $table->integer('month');
            $table->enum('tipe_laporan', ['foto', 'video'])->default(null);
            $table->string('path_storage')->nullable(true);
            $table->foreign('detail_id')
                ->references('id')
                ->on('detail_laporan')
                ->onDelete('cascade');
            $table->foreign('judul_laporan_id')
                ->references('id')
                ->on('judul_laporan')
                ->onDelete('cascade');
            $table->foreign('subjudul_laporan_id')
                ->references('id')
                ->on('subjudul_laporan')
                ->onDelete('cascade');
            $table->index('detail_id');
            $table->index('judul_laporan_id');
            $table->index('subjudul_laporan_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bulanan_laporan');
    }
};
