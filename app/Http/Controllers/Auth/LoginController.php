<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class LoginController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required']
        ]);

        $response = Http::post('http://akhdani.net:12345/api/auth/login?username=' . $credentials['username'] . '&password=' . $credentials['password']);

        if ($response->body() == "true") {
            return redirect()->intended('/perdins');
        } else {
            return back()->with(
                'message',
                'Login Failed',
            );
        }
    }
}
