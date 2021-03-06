<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\PerdinController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::group(
//     [
//         'middleware' => 'guest',
//         'namespace' => 'App\Http\Controllers',
//     ],function($router){
        // Route::get('/', [LoginController::class, 'index'])->name('login');
        // Route::post('/login', [LoginController::class, 'authenticate']);
//     }
// );
Route::get('/', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::resource('/perdins', PerdinController::class);