<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Alert;
use App\Http\Requests\cliente;
use Illuminate\Support\Facades\Storage;

class ClienteControleler extends Controller
{
    public function create()
    {
        try {
            session_start();
            $tipo_d = Http::retry(20, 200)->withToken($_SESSION['B1SESSION'])->get("https://10.170.20.95:50000/b1s/v1/SQLQueries('TipoDoc')/List")->json();
            $tipo_d = $tipo_d['value'];
    
            $departamento = Http::retry(20, 200)->withToken($_SESSION['B1SESSION'])->post("https://10.170.20.95:50000/b1s/v1/SQLQueries('Municipios2')/List")->json();
            $departamento = $departamento['value'];
            
            $codigo_postal = Http::retry(20, 200)->withToken($_SESSION['B1SESSION'])->post("https://10.170.20.95:50000/b1s/v1/SQLQueries('CodigoPostales')/List")->json();
            $codigo_postal = $codigo_postal['value'];
            
            
            $clienteGet =  Http::retry(20, 300)->withToken($_SESSION['B1SESSION'])->get('https://10.170.20.95:50000/b1s/v1/BusinessPartners?$select=FederalTaxID')->json();
            $GetClient = $clienteGet['value'];
            $docClient = [];
            foreach ($GetClient as $key => $value) {
                $docClient[$key] = $value['FederalTaxID'];
            }
            // dd($docClient);
            
            return view('pages.FormCreate', compact('tipo_d', 'departamento', 'codigo_postal', 'docClient'));
        } catch (\Throwable $th) {
            Alert::error('¡Sección Expirada!', 'Iniciar sección nuevamente.');
            return redirect('/');
        }
    }

    public function store(cliente $request)
    {
        
        // ------------- Re-Login Base de Datos-------------------
        $response = Http::retry(20 ,300)->post('https://10.170.20.95:50000/b1s/v1/Login',[
            'CompanyDB' => 'INVERSIONES0804',
            'UserName' => 'Prueba',
            'Password' => '1234',
        ])->json();
    
        // dd($response);
        session_start();
        $_SESSION['B1SESSION'] = $response['SessionId'];

        $ciudad = Http::retry(20, 200)->withToken($_SESSION['B1SESSION'])->post("https://10.170.20.95:50000/b1s/v1/SQLQueries('Municipios2')/List")->json();
        $ciudad = $ciudad['value'];
        // dd($ciudad);

        $datos = $request->all();
        // dd($datos);
        $code = "CN".$datos['Documento'];
        
        $nombre = mb_strtoupper( $datos['Nombre'], 'UTF-8');
        // dd($nombre);


        try {
            $clienteGet =  Http::retry(20, 300)->withToken($_SESSION['B1SESSION'])->get("https://10.170.20.95:50000/b1s/v1/BusinessPartners('".$code."')")->json();
            // dd($clienteGet);
            Alert::error('Error', 'Cliente ya existe');
            return redirect()->route('create');
        } catch (\Throwable $th) {
            $decto = intval($datos['Descuento']);
            try {
                if (isset($datos['Documento_idetidad'])) {
                    $archivos = $datos['Documento_idetidad'];
                    $id_doc = '';
                    foreach ($archivos as $key => $value) {
                        $arch = $value;
                        $nombreDocumento =  time()."-".$code."-".$arch->getClientOriginalName(); 
                        $gl = Storage::disk('public')->put("docs", $nombreDocumento);
                        // $arch->storage(public_path().'/docs', $nombreArch);  
                        $url=url('').'storage/docs';
                        $g = move_uploaded_file($arch, "//10.170.20.124/SAP-compartida/Carpeta_anexos/$nombreDocumento");
                        if ($id_doc == '') {
                            $doc = Http::retry(20, 300)->withToken($_SESSION['B1SESSION'])
                            ->post('https://10.170.20.95:50000/b1s/v1/Attachments2', [
                                'Attachments2_Lines'=> [[
                                        'FileName'=> $nombreDocumento,
                                        'SourcePath'=>  "$url"
                                    ]]
                            ]);
                            $document = $doc->json();
                            $id_doc = $document['AbsoluteEntry'];
                        }else {
                            $AttachmentEntry = $id_doc;
                            $doc = Http::retry(20, 300)->withToken($_SESSION['B1SESSION'])
                            ->patch('https://10.170.20.95:50000/b1s/v1/Attachments2'."($AttachmentEntry)", [
                                'Attachments2_Lines'=> [[
                                    'FileName'=> $nombreDocumento,
                                    'SourcePath'=> "$url"
                                    ]]
                            ]);
                            $id_doc = $AttachmentEntry;
                        }
                    }
                    
                    if ($datos['Doble_dire'] == "no") {
                        foreach ($ciudad as $key => $value) {
                            if ($value['Code']==$datos['Ciudad'][0]) {
                                $city1 = $value['Name'];
                            }
                            if ($value['Code']==$datos['Ciudad'][1]) {
                                $city2 = $value['Name'];
                            }
                        }
                        $create = Http::retry(20, 300)->withToken($_SESSION['B1SESSION'])->post('https://10.170.20.95:50000/b1s/v1/BusinessPartners', [
                                "CardCode"=>$code,//codigocliente colocar CN seguido de numero identificación
                                "CardName"=>$nombre,//Nombre cliente
                                "CardType"=>"L",//Siempre enviar "cLid"
                                "FederalTaxID"=>$datos['Documento'],//numero identificación
                                "Phone1"=>$datos['Telefono'],//solicitar numero celular al cliente
                                "U_HBT_TipDoc"=>$datos['TipoDocumento'],//tipo de identificación de acuerdo a selección de lista desplegable de tipo de identificación ServiceLayerSQL query "TipoDocEcco" 
                                "GroupCode"=>112,//enviar siempre 112
                                "AttachmentEntry" =>$id_doc,//Corresponde al adjunto de fotos o pdf
                                "EmailAddress"=>$datos['Notificaciones'],//Correo electronico que coloque el cliente para factura electronica
                                "U_HBT_MailRecep_FE"=>$datos['Facturacion'],//Correo electronico que coloque el cliente para notificaciones de factura electronica
                                "FreeText"=>$datos['Comentarios'],//colocar los comentarios que requiere adicionar el cliente max 100 caracteres
                                "PriceListNum"=>1,//Falta confirmaciòn de comercial para conocer lista de precios de acuerdo al cliente
                                "DiscountPercent"=>$decto,//Falta confirmaciòn de comercial para conocer lista de precios de acuerdo al cliente
                                "SalesPersonCode"=>$_SESSION['USER'],//Colocar el mismo codigo de quien se logea
                                "U_RB_Clasificacion1"=>$datos['Segmento'],//Se puede enviar el campo quemado desde el desarrollo
                                "BPAddresses"=>[
                                    [  
                                        "AddressName"=>"ENVIO",//Nombre direccion factura Envio
                                        "Street"=>$datos['Direccion'][0],//Dirección completa escrita por el cliente max 100 caracteres
                                        "Block"=>$datos['Barrio'][0],//Barrio
                                        "ZipCode"=>$datos['Codigo_postal'][0],//De la lista desplegable de ServiceLayer SQL Query el campo "Code"
                                        "City"=>$city1,//Ciudad igual seleccionado por el cliente de lista deplegable ServiceLayerSQL query "EccoMunicipios "Code"
                                        "County"=>$datos['Departamento'][0],//De la lista deplegable de ServiceLayerSQL query "EccoMunicipios el campo "U_NomDepartamento"
                                        "AddressType"=>"bo_ShipTo",//Tipo dirección factura envío
                                        "U_HBT_MunMed"=>$datos['Ciudad'][0]//municipio o ciudad de acuerdo a lista desplegable ServiceLayerSQL query "EccoMunicipios" "Code"
                                    ],
                                    [   
                                        "AddressName"=>"FACTURA",//Nombre direccion factura Envio
                                        "Street"=>$datos['Direccion'][1],//Dirección completa escrita por el cliente max 100 caracteres
                                        "Block"=>$datos['Barrio'][1],//Barrio
                                        "ZipCode"=>$datos['Codigo_postal'][1],//De la lista desplegable de ServiceLayer SQL Query el campo "Code"
                                        "City"=>$city2,//Ciudad igual seleccionado por el cliente de lista deplegable ServiceLayerSQL query "EccoMunicipios "Code"
                                        "County"=>$datos['Departamento'][1],//De la lista deplegable de ServiceLayerSQL query "EccoMunicipios el campo "U_NomDepartamento"
                                        "AddressType"=>"bo_BillTo",//Tipo dirección factura envío
                                        "U_HBT_MunMed"=>$datos['Ciudad'][1]//municipio o ciudad de acuerdo a lista desplegable ServiceLayerSQL query "EccoMunicipios" "Code"
                                    ]
                                ]
                            
                        ])->json();
                    }else {
                        foreach ($ciudad as $key => $value) {
                            if ($value['Code']==$datos['Ciudad'][0]) {
                                $city = $value['Name'];
                            }
                        }
                        $create = Http::retry(20, 300)->withToken($_SESSION['B1SESSION'])->post('https://10.170.20.95:50000/b1s/v1/BusinessPartners', [
                            "CardCode"=>$code,//codigocliente colocar CN seguido de numero identificación
                            "CardName"=>$nombre,//Nombre cliente
                            "CardType"=>"L",//Siempre enviar "cLid"
                            "FederalTaxID"=>$datos['Documento'],//numero identificación
                            "Phone1"=>$datos['Telefono'],//solicitar numero celular al cliente
                            "U_HBT_TipDoc"=>$datos['TipoDocumento'],//tipo de identificación de acuerdo a selección de lista desplegable de tipo de identificación ServiceLayerSQL query "TipoDocEcco" 
                            "GroupCode"=>112,//enviar siempre 112
                            "AttachmentEntry" =>$id_doc,//Corresponde al adjunto de fotos o pdf
                            "EmailAddress"=>$datos['Notificaciones'],//Correo electronico que coloque el cliente para factura electronica
                            "U_HBT_MailRecep_FE"=>$datos['Facturacion'],//Correo electronico que coloque el cliente para notificaciones de factura electronica
                            "FreeText"=>$datos['Comentarios'],//colocar los comentarios que requiere adicionar el cliente max 100 caracteres
                            "PriceListNum"=>1,//Falta confirmaciòn de comercial para conocer lista de precios de acuerdo al cliente
                            "DiscountPercent"=>$decto,//Falta confirmaciòn de comercial para conocer lista de precios de acuerdo al cliente
                            "SalesPersonCode"=>$_SESSION['USER'],//Colocar el mismo codigo de quien se logea
                            "U_RB_Clasificacion1"=>$datos['Segmento'],//Se puede enviar el campo quemado desde el desarrollo
                            "BPAddresses"=>[
                                [  
                                    "AddressName"=>"ENVIO",//Nombre direccion factura Envio
                                    "Street"=>$datos['Direccion'][0],//Dirección completa escrita por el cliente max 100 caracteres
                                    "Block"=>$datos['Barrio'][0],//Barrio
                                    "ZipCode"=>$datos['Codigo_postal'][0],//De la lista desplegable de ServiceLayer SQL Query el campo "Code"
                                    "City"=>$city,//Ciudad igual seleccionado por el cliente de lista deplegable ServiceLayerSQL query "EccoMunicipios "Code"
                                    "County"=>$datos['Departamento'][0],//De la lista deplegable de ServiceLayerSQL query "EccoMunicipios el campo "U_NomDepartamento"
                                    "AddressType"=>"bo_ShipTo",//Tipo dirección factura envío
                                    "U_HBT_MunMed"=>$datos['Ciudad'][0]//municipio o ciudad de acuerdo a lista desplegable ServiceLayerSQL query "EccoMunicipios" "Code"
                                ],
                                [   
                                    "AddressName"=>"FACTURA",//Nombre direccion factura Envio
                                    "Street"=>$datos['Direccion'][0],//Dirección completa escrita por el cliente max 100 caracteres
                                    "Block"=>$datos['Barrio'][0],//Barrio
                                    "ZipCode"=>$datos['Codigo_postal'][0],//De la lista desplegable de ServiceLayer SQL Query el campo "Code"
                                    "City"=>$city,//Ciudad igual seleccionado por el cliente de lista deplegable ServiceLayerSQL query "EccoMunicipios "Code"
                                    "County"=>$datos['Departamento'][0],//De la lista deplegable de ServiceLayerSQL query "EccoMunicipios el campo "U_NomDepartamento"
                                    "AddressType"=>"bo_BillTo",//Tipo dirección factura envío
                                    "U_HBT_MunMed"=>$datos['Ciudad'][0]//municipio o ciudad de acuerdo a lista desplegable ServiceLayerSQL query "EccoMunicipios" "Code"
                                ]
                            ]
                        
                        ])->json();
                    }
                }else {
                    if ($datos['Doble_dire'] == "no") {
                        foreach ($ciudad as $key => $value) {
                            if ($value['Code']==$datos['Ciudad'][0]) {
                                $city1 = $value['Name'];
                            }
                            if ($value['Code']==$datos['Ciudad'][1]) {
                                $city2 = $value['Name'];
                            }
                        }
                        $create = Http::retry(20, 300)->withToken($_SESSION['B1SESSION'])->post('https://10.170.20.95:50000/b1s/v1/BusinessPartners', [
                                "CardCode"=>$code,//codigocliente colocar CN seguido de numero identificación
                                "CardName"=>$nombre,//Nombre cliente
                                "CardType"=>"L",//Siempre enviar "cLid"
                                "FederalTaxID"=>$datos['Documento'],//numero identificación
                                "Phone1"=>$datos['Telefono'],//solicitar numero celular al cliente
                                "U_HBT_TipDoc"=>$datos['TipoDocumento'],//tipo de identificación de acuerdo a selección de lista desplegable de tipo de identificación ServiceLayerSQL query "TipoDocEcco" 
                                "GroupCode"=>112,//enviar siempre 112
                                "EmailAddress"=>$datos['Notificaciones'],//Correo electronico que coloque el cliente para factura electronica
                                "U_HBT_MailRecep_FE"=>$datos['Facturacion'],//Correo electronico que coloque el cliente para notificaciones de factura electronica
                                "FreeText"=>$datos['Comentarios'],//colocar los comentarios que requiere adicionar el cliente max 100 caracteres
                                "PriceListNum"=>1,//Falta confirmaciòn de comercial para conocer lista de precios de acuerdo al cliente
                                "DiscountPercent"=>$decto,//Falta confirmaciòn de comercial para conocer lista de precios de acuerdo al cliente
                                "SalesPersonCode"=>$_SESSION['USER'],//Colocar el mismo codigo de quien se logea
                                "U_RB_Clasificacion1"=>$datos['Segmento'],//Se puede enviar el campo quemado desde el desarrollo
                                "BPAddresses"=>[
                                    [  
                                        "AddressName"=>"ENVIO",//Nombre direccion factura Envio
                                        "Street"=>$datos['Direccion'][0],//Dirección completa escrita por el cliente max 100 caracteres
                                        "Block"=>$datos['Barrio'][0],//Barrio
                                        "ZipCode"=>$datos['Codigo_postal'][0],//De la lista desplegable de ServiceLayer SQL Query el campo "Code"
                                        "City"=>$city1,//Ciudad igual seleccionado por el cliente de lista deplegable ServiceLayerSQL query "EccoMunicipios "Code"
                                        "County"=>$datos['Departamento'][0],//De la lista deplegable de ServiceLayerSQL query "EccoMunicipios el campo "U_NomDepartamento"
                                        "AddressType"=>"bo_ShipTo",//Tipo dirección factura envío
                                        "U_HBT_MunMed"=>$datos['Ciudad'][0]//municipio o ciudad de acuerdo a lista desplegable ServiceLayerSQL query "EccoMunicipios" "Code"
                                    ],
                                    [   
                                        "AddressName"=>"FACTURA",//Nombre direccion factura Envio
                                        "Street"=>$datos['Direccion'][1],//Dirección completa escrita por el cliente max 100 caracteres
                                        "Block"=>$datos['Barrio'][1],//Barrio
                                        "ZipCode"=>$datos['Codigo_postal'][1],//De la lista desplegable de ServiceLayer SQL Query el campo "Code"
                                        "City"=>$city2,//Ciudad igual seleccionado por el cliente de lista deplegable ServiceLayerSQL query "EccoMunicipios "Code"
                                        "County"=>$datos['Departamento'][1],//De la lista deplegable de ServiceLayerSQL query "EccoMunicipios el campo "U_NomDepartamento"
                                        "AddressType"=>"bo_BillTo",//Tipo dirección factura envío
                                        "U_HBT_MunMed"=>$datos['Ciudad'][1]//municipio o ciudad de acuerdo a lista desplegable ServiceLayerSQL query "EccoMunicipios" "Code"
                                    ]
                                ]
                            
                        ])->json();
                    }else {
                        foreach ($ciudad as $key => $value) {
                            if ($value['Code']==$datos['Ciudad'][0]) {
                                $city = $value['Name'];
                            }
                        }
                        $create = Http::retry(20, 300)->withToken($_SESSION['B1SESSION'])->post('https://10.170.20.95:50000/b1s/v1/BusinessPartners', [
                            "CardCode"=>$code,//codigocliente colocar CN seguido de numero identificación
                            "CardName"=>$nombre,//Nombre cliente
                            "CardType"=>"L",//Siempre enviar "cLid"
                            "FederalTaxID"=>$datos['Documento'],//numero identificación
                            "Phone1"=>$datos['Telefono'],//solicitar numero celular al cliente
                            "U_HBT_TipDoc"=>$datos['TipoDocumento'],//tipo de identificación de acuerdo a selección de lista desplegable de tipo de identificación ServiceLayerSQL query "TipoDocEcco" 
                            "GroupCode"=>112,//enviar siempre 112
                            "EmailAddress"=>$datos['Notificaciones'],//Correo electronico que coloque el cliente para factura electronica
                            "U_HBT_MailRecep_FE"=>$datos['Facturacion'],//Correo electronico que coloque el cliente para notificaciones de factura electronica
                            "FreeText"=>$datos['Comentarios'],//colocar los comentarios que requiere adicionar el cliente max 100 caracteres
                            "PriceListNum"=>1,//Falta confirmaciòn de comercial para conocer lista de precios de acuerdo al cliente
                            "DiscountPercent"=>$decto,//Falta confirmaciòn de comercial para conocer lista de precios de acuerdo al cliente
                            "SalesPersonCode"=>$_SESSION['USER'],//Colocar el mismo codigo de quien se logea
                            "U_RB_Clasificacion1"=>$datos['Segmento'],//Se puede enviar el campo quemado desde el desarrollo
                            "BPAddresses"=>[
                                [  
                                    "AddressName"=>"ENVIO",//Nombre direccion factura Envio
                                    "Street"=>$datos['Direccion'][0],//Dirección completa escrita por el cliente max 100 caracteres
                                    "Block"=>$datos['Barrio'][0],//Barrio
                                    "ZipCode"=>$datos['Codigo_postal'][0],//De la lista desplegable de ServiceLayer SQL Query el campo "Code"
                                    "City"=>$city,//Ciudad igual seleccionado por el cliente de lista deplegable ServiceLayerSQL query "EccoMunicipios "Code"
                                    "County"=>$datos['Departamento'][0],//De la lista deplegable de ServiceLayerSQL query "EccoMunicipios el campo "U_NomDepartamento"
                                    "AddressType"=>"bo_ShipTo",//Tipo dirección factura envío
                                    "U_HBT_MunMed"=>$datos['Ciudad'][0]//municipio o ciudad de acuerdo a lista desplegable ServiceLayerSQL query "EccoMunicipios" "Code"
                                ],
                                [   
                                    "AddressName"=>"FACTURA",//Nombre direccion factura Envio
                                    "Street"=>$datos['Direccion'][0],//Dirección completa escrita por el cliente max 100 caracteres
                                    "Block"=>$datos['Barrio'][0],//Barrio
                                    "ZipCode"=>$datos['Codigo_postal'][0],//De la lista desplegable de ServiceLayer SQL Query el campo "Code"
                                    "City"=>$city,//Ciudad igual seleccionado por el cliente de lista deplegable ServiceLayerSQL query "EccoMunicipios "Code"
                                    "County"=>$datos['Departamento'][0],//De la lista deplegable de ServiceLayerSQL query "EccoMunicipios el campo "U_NomDepartamento"
                                    "AddressType"=>"bo_BillTo",//Tipo dirección factura envío
                                    "U_HBT_MunMed"=>$datos['Ciudad'][0]//municipio o ciudad de acuerdo a lista desplegable ServiceLayerSQL query "EccoMunicipios" "Code"
                                ]
                            ]
                        
                        ])->json();
                    }
                }
                
                Alert::success('Creado', 'Cliente creado exitosamente.');
                return redirect()->route('create');
            } catch (\Throwable $th) {
                log($th->getMessage());
                Alert::error('¡Sección Expirada!', 'Iniciar sección nuevamente.');
                return redirect('/');
            }
        }


    }
}
