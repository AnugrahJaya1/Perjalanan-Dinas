<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\CookieController;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    private CookieController $cookieController;

    public function __construct()
    {
        $this->cookieController = new CookieController();
    }

    public function index()
    {
        $data = $this->cookieController->getCookie();
        if (!$this->cookieController->checkCookie($data)) return redirect()->intended('/perdins');

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required']
        ]);

        try {
            $response = Http::post('http://akhdani.net:12345/api/auth/login?username=' . $credentials['username'] . '&password=' . $credentials['password'])->json();
        } catch (Exception $e) {
            return back()->with(
                'message',
                'Server Error',
            );
        }

        try {
            $pegawai = Http::get('http://akhdani.net:12345/api/pegawai/username/' . $credentials['username'])->json();
        } catch (Exception $e) {
            return back()->with(
                'message',
                'Server Error',
            );
        }

        if ($response) {
            return redirect()->intended('/perdins')
                ->cookie('username', $pegawai['username'], 60)
                ->cookie('nama', $pegawai['nama'], 60)
                ->cookie('unitKerja', $pegawai['unitkerja'], 60);
        } else {
            return back()->with(
                'message',
                'Login Failed',
            );
        }
    }

    public function logout()
    {
        $cookies = $this->cookieController->deleteCookie();

        return redirect()->intended('/')
            ->withCookie($cookies[0]) //username
            ->withCookie($cookies[1]) //nama
            ->withCookie($cookies[2]); //unitKerja
    }
}
