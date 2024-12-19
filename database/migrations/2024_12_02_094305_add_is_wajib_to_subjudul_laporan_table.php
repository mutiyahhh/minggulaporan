<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('subjudul_laporan', function (Blueprint $table) {
            $table->boolean('is_wajib')->default(0); // Default ke 'Tidak Wajib'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('subjudul_laporan', function (Blueprint $table) {
            $table->dropColumn('is_wajib');
        });
    }
};
