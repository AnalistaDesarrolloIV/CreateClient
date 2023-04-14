<?php

namespace App\Http\Controllers;

use App\Http\Requests\loginrq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cookie;
use RealRashid\SweetAlert\Facades\Alert;

class SessionController extends Controller
{
    public function login(loginrq $request)
    {
        try {    
            $data = $request->all();

            // dd($data);
            // ------------- Login Base de Datos-------------------
            // $response = Http::retry(30, 5)->post('https://10.170.20.95:50000/b1s/v1/Login',[
            //     'CompanyDB' => 'INVERSIONES',
            //     'UserName' => 'Desarrollos',
            //     'Password' => 'Asdf1234$',
            // ])->json();

            $response = Http::retry(30, 5)->post('https://10.170.20.95:50000/b1s/v1/Login',[
                'CompanyDB' => 'ZPRUREBANO',
                'UserName' => 'Desarrollos',
                'Password' => 'Asdf1234$',
            ])->json();
        
            session_start();
            $_SESSION['B1SESSION'] = $response['SessionId'];    
            // dd($response);
    
            $users = Http::retry(30, 5)->withToken($_SESSION['B1SESSION'])->get('https://10.170.20.95:50000/b1s/v1/EmployeesInfo?$select= ExternalEmployeeNumber,EmployeeCode,U_HBT_Contrasena,SalesPersonCode,U_GSP_Target,FirstName,LastName')['value'];

            // dd($users);

            foreach ($users as $key => $value) {
                if ($data['usuario'] == $value['EmployeeCode'] && $data['password'] == $value['U_HBT_Contrasena']) {
                    $_SESSION['USER'] = $value['SalesPersonCode'];
                    $_SESSION['NAME_USER'] = $value['FirstName']." ".$value['LastName'];
                    $_SESSION['COBRA'] = $value['U_GSP_Target'];
                    $usuario = $_SESSION['COBRA'];

                    // dd($value['SalesPersonCode']." ".$value['FirstName']." ".$value['LastName']." ".$value['U_GSP_Target']);
                  
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
