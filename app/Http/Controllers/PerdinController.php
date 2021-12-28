<?php

namespace App\Http\Controllers;

use App\Models\Perdin;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Http;

class PerdinController extends Controller
{
    private CookieController $cookieController;

    public function __construct()
    {
        $this->cookieController = new CookieController();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $data = $this->cookieController->getCookie();
        if ($this->cookieController->checkCookie($data)) return redirect()->intended('/');

        $perdins = Perdin::orderBy('status', 'ASC')
        ->orderBy('created_at', 'DESC');
        if ($data[2] != 'SDM') {
            $perdins = $perdins->where('nama_pegawai', 'like', $data[1]);
        }

        if ($request->has('search')) {
            $perdins = $perdins->where('lokasi_tujuan', 'like', "%{$request->search}%")
                ->orWhere('tujuan_perdin', 'like', "%{$request->search}%");
            if ($data[2] == 'SDM') {
                $perdins = $perdins->orWhere('nama_pegawai', 'like', "%{$request->search}%");
            }else{
                $perdins = $perdins->where('nama_pegawai', 'like', $data[1]);
            }
        }

        $perdins = $perdins->get();

        return response(view('perdins.index', compact('perdins')));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = $this->cookieController->getCookie();
        if ($this->cookieController->checkCookie($data)) return redirect()->intended('/');

        try {
            $locations = Http::get('http://akhdani.net:12345/api/lokasi/list')->json();
        } catch (Exception $e) {
            return redirect()->intended('/perdins')->with(
                'error',
                'Server Error, Please try again',
            );
        }

        return view('perdins.create', compact('locations'));
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
            'tujuan_perdin' => ['required', 'string'],
            'tanggal_berangkat' => ['required', 'date', 'after:today'],
            'tanggal_pulang' => ['required', 'date', 'after:tanggal_berangkat'],
            'id_lokasi_tujuan' => ['required', 'integer']
        ]);

        // hitung durasi hari perjalanan dinas
        $durasi = date_diff(date_create($data['tanggal_berangkat']), date_create($data['tanggal_pulang']));
        $durasi = substr($durasi->format("%R%a"), 1);

        // id kota bandung (default)
        $id_lokasi_awal = 345;

        try {
            $lokasi_awal = Http::get('http://akhdani.net:12345/api/lokasi/' . $id_lokasi_awal)->json();
            $lokasi_tujuan = Http::get('http://akhdani.net:12345/api/lokasi/' . $data['id_lokasi_tujuan'])->json();
        } catch (Exception $e) {
            return redirect()->intended('/perdins')->with(
                'error',
                'Server Error, Please try again',
            );;
        }

        // hitung jarak lokasi awal dan lokasi tujuan
        $jarak = $this->hitungJarak($lokasi_awal, $lokasi_tujuan);

        // hitung uang saku
        $uang_saku = $this->hitungUangSaku($jarak, $lokasi_awal, $lokasi_tujuan);

        Perdin::create([
            'tujuan_perdin' => $request['tujuan_perdin'],
            'tanggal_berangkat' => $request['tanggal_berangkat'],
            'tanggal_pulang' => $request['tanggal_pulang'],
            'durasi' => $durasi +1,
            'uang_saku' => $uang_saku,
            'nama_pegawai' => Cookie::get('nama'),
            'lokasi_awal' => $lokasi_awal['nama'],
            'lokasi_tujuan' => $lokasi_tujuan['nama'],
            'id_approval' => null,
            'status' => 0
        ]);

        return redirect()->route('perdins.index')->with('message', 'Perdin Registered Succesfully');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        try {
            $pegawai = Http::get('http://akhdani.net:12345/api/pegawai/username/' . Cookie::get('username'))->json();
        } catch (Exception $e) {
            return redirect()->intended('/perdins')->with(
                'error',
                'Server Error, Please try again',
            );;
        }
        $perdin = Perdin::findOrFail($id);

        if ($pegawai['nama'] == $perdin['nama_pegawai']) {
            return redirect()->route('perdins.index')->with('error', "You can't approve/reject your own perdin");
        }

        $perdin->update([
            'id_approval' => $pegawai['pegawaiid'],
            'status' => ($request->btn == 'approve') ? 1 : 2,
        ]);

        return redirect()->route('perdins.index')->with('message', 'Perdin Approved Succesfully');
    }

    private function hitungUangSaku($jarak, $lokasi_awal, $lokasi_tujuan)
    {
        if ($jarak >= 0 && $jarak <= 60) {
            return 0;
        } else {
            if($lokasi_tujuan['isln']=="Y"){
                return 50; // USD
            } else if ($lokasi_awal['provinsi'] == $lokasi_tujuan['provinsi']) { // satu provinsi
                return 200000;
            } else if (($lokasi_awal['provinsi'] != $lokasi_tujuan['provinsi']) && ($lokasi_awal['pulau'] == $lokasi_tujuan['pulau'])) { // luar provinsi dan satu pulau
                return 250000;
            } else if (($lokasi_awal['provinsi'] != $lokasi_tujuan['provinsi']) && ($lokasi_awal['pulau'] != $lokasi_tujuan['pulau'])) { // luar provinsi dan luar pulau
                return 300000;
            }
        }
    }

    // referensi https://www.geeksforgeeks.org/program-distance-two-points-earth/
    /*
    * return value in KM
    */
    private function hitungJarak($lokasi_awal, $lokasi_tujuan)
    {
        // Converts the number in degrees to the radian equivalent
        $lon1 = deg2rad($lokasi_awal['lon']);
        $lat1 = deg2rad($lokasi_awal['lat']);

        // Converts the number in degrees to the radian equivalent
        $lon2 = deg2rad($lokasi_tujuan['lon']);
        $lat2 = deg2rad($lokasi_tujuan['lat']);

        //Haversine Formula
        $dlong = $lon2 - $lon1;
        $dlati = $lat2 - $lat1;

        $val = pow(sin($dlati / 2), 2) + cos($lat1) * cos($lat2) * pow(sin($dlong / 2), 2);

        $res = 2 * asin(sqrt($val));

        $radius = 3958.756;

        return ($res * $radius * 1.609344);
    }
}
