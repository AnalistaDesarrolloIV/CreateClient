<?php

use App\Http\Controllers\ClienteControleler;
use App\Http\Controllers\SessionController;
use Illuminate\Support\Facades\Http;
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

Route::get('/', function () {
    // $response = Http::retry(20 ,300)->post('https://10.170.20.95:50000/b1s/v1/Login',[
    //     'CompanyDB' => 'INVERSIONES0804',
    //     'UserName' => 'Prueba',
    //     'Password' => '1234',
    // ])->json();

    // dd($response);
    // session_start();
    // $_SESSION['B1SESSION'] = $response['SessionId'];
    // dd($_SESSION['B1SESSION']);  
    

    // $datos = Http::get('https://mandaryservir.co/mys/users/remesasivanagro/2022-09-15')->json();
    // $datos = $datos['Guia'];
    // dd($datos);
        
    return view('login');
});

Route::get('/logout', [SessionController::class, 'logout'])->name('logout');
Route::post('/login', [SessionController::class, 'login'])->name('login');


Route::get('/create', [ClienteControleler::class, 'create'])->name('create');
Route::post('/store', [ClienteControleler::class, 'store'])->name('store');
