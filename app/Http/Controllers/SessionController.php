<?php

namespace App\Http\Controllers;

use App\Http\Requests\loginrq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
Use Alert;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cookie;

class SessionController extends Controller
{
    public function login(loginrq $request)
    {
        try {    
            $data = $request->all();

            // ------------- Login Base de Datos-------------------
            $response = Http::retry(20 ,300)->post('https://10.170.20.95:50000/b1s/v1/Login',[
                'CompanyDB' => 'INVERSIONES0804',
                'UserName' => 'Prueba',
                'Password' => '1234',
            ])->json();
        
            // dd($response);
            session_start();
            $_SESSION['B1SESSION'] = $response['SessionId'];    
    
    
            $users = Http::retry(10, 200)->withToken($_SESSION['B1SESSION'])->get('https://10.170.20.95:50000/b1s/v1/EmployeesInfo?$select= ExternalEmployeeNumber,EmployeeCode,U_HBT_Contrasena,SalesPersonCode')->json();
            $users = $users['value'];
            // dd($users);
            foreach ($users as $key => $value) {
                if ($data['usuario'] == $value['EmployeeCode'] && $data['password'] == $value['U_HBT_Contrasena']) {
                    $_SESSION['USER'] = $value['SalesPersonCode'];
                    $usuario = $_SESSION['USER'];
                  
                    return redirect()->route('create');
                }
            }
            Alert::error('error', 'La información no coinside con con nuestros registros.');
            return view('login');
        } catch (\Throwable $th) {
            Alert::error('error', 'Algo fallo reintentalo.');
            return redirect('/');
        }
    
    }


    public function logout()
    {
        session_start();
        session_destroy();
        return redirect('/');
    }
}
