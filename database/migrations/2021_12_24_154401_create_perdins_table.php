<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePerdinsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('perdins', function (Blueprint $table) {
            $table->id();
            $table->string('alasan_perdin');
            $table->date('tanggal_berangkat');
            $table->date('tanggal_pulang');
            $table->integer('durasi');
            $table->integer('uang_saku');
            $table->string('nama_pegawai');
            $table->string('lokasi_awal');
            $table->string('lokasi_tujuan');
            $table->string('id_approval')->nullable();
            $table->boolean('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('perdins');
    }
}
