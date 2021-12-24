<?php

namespace App\Http\Controllers;

use App\Models\Perdin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Http;

class PerdinController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // dd(Cookie::get('username'));
        $pegawai = Http::get('http://akhdani.net:12345/api/pegawai/username/' . Cookie::get('username'))->json();

        $perdins = Perdin::where('id_pegawai', $pegawai['pegawaiid'])->get();
        
        return response(view('perdins.index', compact('perdins')))->cookie('username', $pegawai['username'], 60);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $response = Http::get('http://akhdani.net:12345/api/lokasi/list')->json();

        return view('perdins.create', compact('response'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'alasan_perdin' => ['required', 'string'],
            'tanggal_berangkat' => ['required', 'date'],
            'tanggal_pulang' => ['required', 'date'],
            'id_lokasi_tujuan' => ['required', 'integer']
        ]);

        // dd($data);

        $durasi = date_diff(date_create($data['tanggal_berangkat']), date_create($data['tanggal_pulang']));
        $durasi = substr($durasi->format("%R%a"), 1);

        $uang_saku = 100000;

        $pegawai = Http::get('http://akhdani.net:12345/api/pegawai/username/' . Cookie::get('username'))->json();
        $id_peangawai = $pegawai['pegawaiid'];

        $id_lokasi_awal = 345; // id kota bandung (default)

        Perdin::create([
            'alasan_perdin' => $request['alasan_perdin'],
            'tanggal_berangkat' => $request['tanggal_berangkat'],
            'tanggal_pulang' => $request['tanggal_pulang'],
            'durasi' => $durasi*1,
            'uang_saku' => $uang_saku,
            'id_pegawai' => $id_peangawai*1,
            'id_lokasi_awal' => $id_lokasi_awal,
            'id_lokasi_tujuan' => $request['id_lokasi_tujuan']*1,
            'id_approval' => null,
            'status' => 0
        ]);

        return redirect()->route('perdins.index')->with('message', 'Perdin Registered Succesfully')->cookie('username', $pegawai['username'], 60);
    }
}
