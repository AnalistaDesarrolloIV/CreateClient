<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Requests\cliente;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;

class ClienteControleler extends Controller
{
    public function create()
    {
        session_start();
        try {
            $tipo_d = Http::retry(30, 5)->withToken($_SESSION['B1SESSION'])->get("https://10.170.20.95:50000/b1s/v1/SQLQueries('TipoDoc')/List")['value'];
    
            $departamento = Http::retry(30, 5)->withToken($_SESSION['B1SESSION'])->post("https://10.170.20.95:50000/b1s/v1/SQLQueries('Municipios2')/List")['value'];
            
            $codigo_postal = Http::retry(30, 5)->withToken($_SESSION['B1SESSION'])->post("https://10.170.20.95:50000/b1s/v1/SQLQueries('CodigoPostales')/List")['value'];
            
            $clienteGet =  Http::retry(30, 5)->withToken($_SESSION['B1SESSION'])->get('https://10.170.20.95:50000/b1s/v1/BusinessPartners?$select=FederalTaxID&$filter=CardType eq'."'cCustomer'")['value'];

            $Getgrupos =  Http::retry(30, 5)->withToken($_SESSION['B1SESSION'])->get("https://10.170.20.95:50000/b1s/v1/SQLQueries('IV_GRUPART')/List")['value'];

            $Getsegmentos =  Http::retry(30, 5)->withToken($_SESSION['B1SESSION'])->get("https://10.170.20.95:50000/b1s/v1/sml.svc/SEGMENTOS")['value'];
            // dd($Getsegmentos);
            $docClient = [];
            foreach ($clienteGet as $key => $value) {
                $docClient[$key] = $value['FederalTaxID'];
            }

            $usuario = $_SESSION['NAME_USER']; 
            
            return view('pages.FormCreate', compact('usuario', 'tipo_d', 'departamento', 'codigo_postal', 'docClient', 'Getgrupos', 'Getsegmentos'));

        } catch (\Throwable $th) {
            session_destroy();
            Alert::error('¡Sección Expirada!', 'Iniciar sección nuevamente.');
            return redirect('/');
        }
    }

    public function store(cliente $request)
    {
        
        session_start();

        $datos = $request->all();
        // dd($datos);
        $param = explode("--", $datos['grupos']);

        $groupID = $param[0];
        $groupName = $param[1];

        // ------------- Re-Login Base de Datos-------------------
        $response = Http::retry(30 ,5)->post('https://10.170.20.95:50000/b1s/v1/Login',[
            'CompanyDB' => 'INVERSIONES',
            'UserName' => 'Desarrollos',
            'Password' => 'Asdf1234$',
        ])->json();
    
        // $response = Http::retry(30, 5)->post('https://10.170.20.95:50000/b1s/v1/Login',[
        //     'CompanyDB' => 'ZPRUREBANO',
        //     'UserName' => 'Desarrollos',
        //     'Password' => 'Asdf1234$',
        // ])->json();

        $_SESSION['B1SESSION'] = $response['SessionId'];

        // dd($response['SessionId']);

        $ciudad = Http::retry(30, 5)->withToken($_SESSION['B1SESSION'])->post("https://10.170.20.95:50000/b1s/v1/SQLQueries('Municipios2')/List")['value'];

        $grupos =  Http::retry(30, 5)->withToken($_SESSION['B1SESSION'])->get("https://10.170.20.95:50000/b1s/v1/SQLQueries('IV_SEGMENTOVENTA')/List")['value'];
        
        $segmento = $datos['Segmento'].$groupName;

        // dd($segmento);

        foreach ($grupos as $key => $dtos) {
            if ($dtos['Code'] == $segmento) {
                $seg = $dtos['Code'];
                $descuento = $dtos['U_GSP_NAME'];
            }
        }

        // dd($descuento);

        $code = "CN".$datos['Documento'];
        
        $nombre = mb_strtoupper( $datos['Nombre'], 'UTF-8');

        try {
            $clienteGet =  Http::retry(30, 5)->withToken($_SESSION['B1SESSION'])->get("https://10.170.20.95:50000/b1s/v1/BusinessPartners('".$code."')")->json();
            
            Alert::error('Error', 'Cliente ya existe');
            return redirect()->route('create');

        } catch (\Throwable $th) {
            $descuento = intval($descuento);
            try {
                if (isset($datos['Documento_idetidad'])) {                    
                    $archivos = $datos['Documento_idetidad'];
                    $id_doc = '';
                    $n_ciclo = 0;
                    foreach ($archivos as $key => $value) {
                        $arch = $value;
                        // dd($n_ciclo);

                        // $nombreDocumento = "Cedula_o_RUT_".$code."_".date('Y-m-d H_i_s')."_";
                        // $nombreDocumento = $code."_".$arch->getClientOriginalName();
                        $name_doc = $datos['NameDoc'][$n_ciclo]."-".$code;
                        // dd($name_doc);
                        $nombreDocumento = str_replace('/', '_', $name_doc);

                        $url= "xampp/tmps";
                        
                        $directory = "//mnt/anexos/";
                        // $directory = "//10.170.20.124/SAP-compartida/Carpeta_anexos/";


                        $g = move_uploaded_file($arch, $directory. $nombreDocumento);	

                        if ($id_doc == '') {
                            $doc = Http::retry(30, 5)->withToken($_SESSION['B1SESSION'])
                            ->post('https://10.170.20.95:50000/b1s/v1/Attachments2', [
                                'Attachments2_Lines'=> [[
                                        'FileName'=> $nombreDocumento,
                                        'SourcePath'=>  $url
                                    ]]
                            ]);
                            $document = $doc->json();
                            $id_doc = $document['AbsoluteEntry'];
                        }else {
                            $AttachmentEntry = $id_doc;
                            $doc = Http::retry(30, 5)->withToken($_SESSION['B1SESSION'])
                            ->patch('https://10.170.20.95:50000/b1s/v1/Attachments2'."($AttachmentEntry)", [
                                'Attachments2_Lines'=> [[
                                    'FileName'=> $nombreDocumento,
                                    'SourcePath'=> $url
                                    ]]
                            ]);
                            $id_doc = $AttachmentEntry;
                        }
                        
                        $n_ciclo += 1; 
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
                        $create = Http::retry(30, 5)->withToken($_SESSION['B1SESSION'])->post('https://10.170.20.95:50000/b1s/v1/BusinessPartners', [
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
                                "DiscountPercent"=>$descuento,//Falta confirmaciòn de comercial para conocer lista de precios de acuerdo al cliente
                                "SalesPersonCode"=>$_SESSION['USER'],//Colocar el mismo codigo de quien se logea
                                "U_RB_Clasificacion1"=>$datos['Segmento'],//Se puede enviar el campo quemado desde el desarrollo
                                "GroupCode"=> $groupID,
                                "U_IV_Cobrador"=> $_SESSION['COBRA'],
                                "UseShippedGoodsAccount"=> "tYES",
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
                                        "U_HBT_MunMed"=>$datos['Ciudad'][1],//municipio o ciudad de acuerdo a lista desplegable ServiceLayerSQL query "EccoMunicipios" "Code"
                                        "U_HBT_DirMM"=>"Y"
                                    ]
                                ]
                            
                        ])->json();
                    }else {
                        foreach ($ciudad as $key => $value) {
                            if ($value['Code']==$datos['Ciudad'][0]) {
                                $city = $value['Name'];
                            }
                        }
                        $create = Http::retry(30, 5)->withToken($_SESSION['B1SESSION'])->post('https://10.170.20.95:50000/b1s/v1/BusinessPartners', [
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
                            "DiscountPercent"=>$descuento,//Falta confirmaciòn de comercial para conocer lista de precios de acuerdo al cliente
                            "SalesPersonCode"=>$_SESSION['USER'],//Colocar el mismo codigo de quien se logea
                            "U_RB_Clasificacion1"=>$datos['Segmento'],//Se puede enviar el campo quemado desde el desarrollo
                            "GroupCode"=> $groupID,
                            "U_IV_Cobrador"=> $_SESSION['COBRA'],
                            "UseShippedGoodsAccount"=> "tYES",
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
                                    "U_HBT_MunMed"=>$datos['Ciudad'][0],//municipio o ciudad de acuerdo a lista desplegable ServiceLayerSQL query "EccoMunicipios" "Code"
                                    "U_HBT_DirMM"=>"Y"
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
                        $create = Http::retry(30, 5)->withToken($_SESSION['B1SESSION'])->post('https://10.170.20.95:50000/b1s/v1/BusinessPartners', [
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
                                "DiscountPercent"=>$descuento,//Falta confirmaciòn de comercial para conocer lista de precios de acuerdo al cliente
                                "SalesPersonCode"=>$_SESSION['USER'],//Colocar el mismo codigo de quien se logea
                                "U_RB_Clasificacion1"=>$datos['Segmento'],//Se puede enviar el campo quemado desde el desarrollo
                                "GroupCode"=> $groupID,
                                "U_IV_Cobrador"=> $_SESSION['COBRA'],
                                "UseShippedGoodsAccount"=> "tYES",
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
                                        "U_HBT_MunMed"=>$datos['Ciudad'][1],//municipio o ciudad de acuerdo a lista desplegable ServiceLayerSQL query "EccoMunicipios" "Code"
                                        "U_HBT_DirMM"=>"Y"
                                    ]
                                ]
                            
                        ])->json();
                    }else {
                        foreach ($ciudad as $key => $value) {
                            if ($value['Code']==$datos['Ciudad'][0]) {
                                $city = $value['Name'];
                            }
                        }
                        $create = Http::retry(30, 5)->withToken($_SESSION['B1SESSION'])->post('https://10.170.20.95:50000/b1s/v1/BusinessPartners', [
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
                            "DiscountPercent"=>$descuento,//Falta confirmaciòn de comercial para conocer lista de precios de acuerdo al cliente
                            "SalesPersonCode"=>$_SESSION['USER'],//Colocar el mismo codigo de quien se logea
                            "U_RB_Clasificacion1"=>$datos['Segmento'],//Se puede enviar el campo quemado desde el desarrollo
                            "GroupCode"=> $groupID,
                            "U_IV_Cobrador"=> $_SESSION['COBRA'],
                            "UseShippedGoodsAccount"=> "tYES",
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
                                    "U_HBT_MunMed"=>$datos['Ciudad'][0],//municipio o ciudad de acuerdo a lista desplegable ServiceLayerSQL query "EccoMunicipios" "Code"
                                    "U_HBT_DirMM"=>"Y"
                                ]
                            ]
                        
                        ])->json();
                    }
                }
                
                Alert::success('Creado', 'Cliente creado exitosamente.');
                return redirect()->route('create');
            } catch (\Throwable $th) {
                session_destroy();                
                Alert::error('¡Sección Expirada!', 'Iniciar sección nuevamente.');
                return redirect('/');
            }
        }


    }
}
