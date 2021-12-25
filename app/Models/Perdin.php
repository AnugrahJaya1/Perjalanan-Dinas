<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perdin extends Model
{
    use HasFactory;

    protected $fillable =[
        'alasan_perdin',
        'tanggal_berangkat',
        'tanggal_pulang',
        'durasi',
        'uang_saku',
        'nama_pegawai',
        'lokasi_awal',
        'lokasi_tujuan',
        'id_approval',
        'status'
    ];
}
