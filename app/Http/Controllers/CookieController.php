<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class CookieController extends Controller
{
    public function checkCookie($data)
    {
        $username = $data[0];
        $nama = $data[1];
        $unitKerja = $data[2];

        if (is_null($username) && is_null($nama) && is_null($unitKerja)) {
            return true;
        } else {
            return false;
        }
    }

    public function getCookie()
    {
        return [
            Cookie::get('username'),
            Cookie::get('nama'),
            Cookie::get('unitKerja')
        ];
    }
    
    public function deleteCookie()
    {
        return [
            Cookie::forget('username'),
            Cookie::forget('nama'),
            Cookie::forget('unitKerja')
        ];
    }
}
