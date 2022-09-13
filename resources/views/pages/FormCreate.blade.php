@extends('welcome')

@section('tittle', 'Crear cliente')

@section('content')
<div class="row justify-content-center mt-5">
    <div class=" col-12 col-lg-10 contenedor rounded p-4">
        <form action="{{route('store')}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-12">
                    <h3 class="text-center" style="font-size: 35px;"> <strong> Información Personal. </strong> </h3>
                </div>
                <div class="col-lg-6">
                    <div class="mb-3">
                        <label for="tipo_doc" class="form-label"> <strong> Tipo documento <b style="color: red;">*</b> </strong></label>
                        <select class="form-select form-select-lg select2  @error('TipoDocumento') is-invalid @enderror" id="tipo_doc" name="TipoDocumento" data-placeholder="Seleccionar" onchange="archivos()">
                            <option value=''>Seleccione</option>
                        </select>
                    </div>
                    @error('TipoDocumento')
                    <div class="alert alert-danger mt-1 mb-1"><small>{{ $message }}</small></div>
                    @enderror
                </div>
                <div class="col-lg-6">
                    <div class="mb-3">
                        <label for="Doc" class="form-label"><strong> N° documento <b style="color: red;">*</b> </strong></label>
                        <input type="text" class="form-control form-control-lg @error('Documento') is-invalid @enderror" id="Doc" value="{{old('Documento')}}" name="Documento" placeholder="Ejm. 1005687427" onchange="validar()">
                    </div>
                    <div id="mensaje">

                    </div>
                    @error('Documento')
                    <div class="alert alert-danger mt-1 mb-1"><small>{{ $message }}</small></div>
                    @enderror
                </div>
                <div class="col-12">
                    <div class="mb-3">
                        <label for="nane" class="form-label"><strong> Nombre completo <b style="color: red;">*</b> </strong></label>
                        <input type="text" class="form-control form-control-lg @error('Nombre') is-invalid @enderror" id="nane" name="Nombre" value="{{old('Nombre')}}" placeholder="Ejm. IVAN MONTES" onkeyup="mayusculas()">
                    </div>
                    @error('Nombre')
                    <div class="alert alert-danger mt-1 mb-1"><small>{{ $message }}</small></div>
                    @enderror
                </div>
                <div class="col-lg-6">
                    <div class="mb-3">
                        <label for="tel" class="form-label"><strong> Telefono/Celular <b style="color: red;">*</b> </strong></label>
                        <input type="number" class="form-control form-control-lg @error('Telefono') is-invalid @enderror" id="tel" name="Telefono" value="{{old('Telefono')}}" placeholder="Ejm. 325698412">
                    </div>
                    @error('Telefono')
                    <div class="alert alert-danger mt-1 mb-1"><small>{{ $message }}</small></div>
                    @enderror
                </div>
                <div class="col-8 col-lg-4">
                    <div class="mb-3">
                        <label for="segmento" class="form-label"> <strong> Segmento <b style="color: red;">*</b> </strong></label>
                        <select class="form-select form-select-lg select2 @error('Segmento') is-invalid @enderror" id="segmento" name="Segmento" data-placeholder="Seleccionar" onchange="dcto()">
                            <option value=''>Seleccione</option>
                            <option value="Agropecuaria">Agropecuaria</option>
                            <option value="Aves - Engorde">Aves - Engorde</option>
                            <option value="Aves - Ponedoras">Aves - Ponedoras</option>
                            <option value="Clinica veterinaria">Clinica veterinaria</option>
                            <option value="Equinos - Criadero">Equinos - Criadero</option>
                            <option value="Equinos - Pesebrera">Equinos - Pesebrera</option>
                            <option value="Ganaderia - Carne">Ganaderia - Carne</option>
                            <option value="Ganaderia - Doble proposito">Ganaderia - Doble proposito</option>
                            <option value="Ganaderia - Leche">Ganaderia - Leche</option>
                            <option value="Mascotas - Animales de compañía">Mascotas - Animales de compañía</option>
                            <option value="Mascotas - Pet shop">Mascotas - Pet shop</option>
                            <option value="Porcicultura - Ceba">Porcicultura - Ceba</option>
                            <option value="Porcicultura - Ciclo completo">Porcicultura - Ciclo completo</option>
                            <option value="Porcicultura - Cria">Porcicultura - Cria</option>
                            <option value="Porcicultura - Levante">Porcicultura - Levante</option>
                            <option value="Veterinario a domicilio">Veterinario a domicilio</option>
                            <option value="Otros">Otros</option>
                        </select>
                    </div>
                    @error('Segmento')
                    <div class="alert alert-danger mt-1 mb-1"><small>{{ $message }}</small></div>
                    @enderror
                </div>
                <div class="col-4 col-lg-2">
                    <div class="mb-3">
                        <label for="descuento" class="form-label"><strong> Dcto. </strong></label>
                        <input type="number" class="form-control form-control-lg @error('Descuento') is-invalid @enderror" readonly id="descuento" name="Descuento" value="{{old('Descuento')}}" placeholder="">
                    </div>
                    @error('Descuento')
                    <div class="alert alert-danger mt-1 mb-1"><small>{{ $message }}</small></div>
                    @enderror
                </div>
                <div class="col-lg-6">
                    <div class="mb-3">
                        <label for="correo_fac" class="form-label"><strong> Correo Facturación <b style="color: red;">*</b> </strong></label>
                        <input type="email" class="form-control form-control-lg @error('Facturacion') is-invalid @enderror" id="correo_fac" name="Facturacion" value="{{old('Facturacion')}}" {{-- onchange="Valid_email()" --}} placeholder="Ejm. factura@example.com">
                    </div>
                    
                    {{-- <div id="mensaje1">
                        
                    </div> --}}

                    @error('Facturacion')
                    <div class="alert alert-danger mt-1 mb-1"><small>{{ $message }}</small></div>
                    @enderror
                </div>
                <div class="col-lg-6">
                    <div class="mb-3">
                        <label for="correo_noti" class="form-label"><strong> Correo Notificaciones <b style="color: red;">*</b> </strong></label>
                        <input type="email" class="form-control form-control-lg @error('Notificaciones') is-invalid @enderror" id="correo_noti" name="Notificaciones" value="{{old('Notificaciones')}}" {{-- onchange="Valid_email2()" --}} placeholder="Ejm. Notificacion@example.com">
                    </div>
                    
                    {{-- <div id="mensaje2">
                        
                    </div> --}}

                    @error('Notificaciones')
                    <div class="alert alert-danger mt-1 mb-1"><small>{{ $message }}</small></div>
                    @enderror
                </div>
                <div class="col-12">
                    <div class="mb-3">
                        <label for="coments" class="form-label"> <strong> Comentarios </label>
                        <textarea class="form-control @error('Comentarios') is-invalid @enderror" id="coments" name="Comentarios" rows="3" placeholder="Ejm. QUIERO OTRA DIRECCIÓN">{{old('Comentarios')}}</textarea>
                    </div>
                    @error('Comentarios')
                    <div class="alert alert-danger mt-1 mb-1"><small>{{ $message }}</small></div>
                    @enderror
                </div>
                <div class="row">
                    <label for="" class="form-label"><b> IVA </b><b style="color: red;">*</b></label>
                    <div class="col-12 mb-3 ml-2 rounded bg-light">
                        <p class="form-label"> <strong>Es responsable de Iva: </strong> </p>
                        <div class="form-check py-2">
                            <input class="form-check-input" type="radio" name="Res_Iva" id="si" value="si" onclick="archivos2()">
                            <label class="form-check-label" for="si">Si</label>
                        </div>
                        <div class="form-check py-2">
                            <input class="form-check-input" type="radio" name="Res_Iva" id="no" value="no" onclick="borrararch2()">
                            <label class="form-check-label" for="no">No</label>
                        </div>
                        @error('Res_Iva')
                        <div class="alert alert-danger mt-1 mb-1"><small>{{ $message }}</small></div>
                        @enderror
                    </div>
                </div>
                <div class="col-12" id="archi">

                </div>
                <div class="col-12" id="archi2">

                </div>
                <div class="col"></div>
            </div>
            <hr>
            <div class="row">
                <div class="col-12">
                    <h3 class="text-center" style="font-size: 35px;"> <strong> Direcciones. </strong> </h3>
                </div>
                <div class="col-12">
                    <h5 class="text-start"> <strong> envío. </strong> </h5>
                </div>
                <hr>
                <input type="hidden" name="Tipo_direccion[]" value="bo_ShipTo">
                <input type="hidden" name="Nombre_direccion[]" value="ENVIO">

                <div class="col-lg-6">
                    <label for="depar" class="form-label"> <strong> Departamento </strong><b style="color: red;">*</b></label>
                    <select class="form-select form-select-lg select2 @error('Departamento') is-invalid @enderror" id="depar" name="Departamento[]" data-placeholder="Seleccionar" onchange="Mupio()" required>
                        <option value=''>Seleccione</option>
                    </select>
                        @error('Departamento')
                            <div class="alert alert-danger mt-1 mb-1"><small>{{ $message }}</small></div>
                        @enderror
                </div>
                <div class="col-lg-6">
                    <label for="ciudad" class="form-label"> <strong> Municipio/Ciudad </strong><b style="color: red;">*</b></label>
                    <select class="form-select form-select-lg select2 @error('Ciudad') is-invalid @enderror" id="ciudad" name="Ciudad[]" data-placeholder="Seleccionar" required>
                        <option value=''>Seleccione</option>
                    </select>
                        @error('Ciudad')
                            <div class="alert alert-danger mt-1 mb-1"><small>{{ $message }}</small></div>
                        @enderror
                </div>
                <div class="col-lg-6">
                    <div class="mb-3">
                        <label for="barrio" class="form-label"><strong> Barrio/Vereda </strong><b style="color: red;">*</b></label>
                        <input type="text" class="form-control form-control-lg @error('Barrio') is-invalid @enderror" id="barrio" name="Barrio[]" placeholder="Ejm. Poblado" onkeyup="mayusculas_barrio()" required>
                    </div>
                        @error('Barrio')
                            <div class="alert alert-danger mt-1 mb-1"><small>{{ $message }}</small></div>
                        @enderror
                </div>
                <div class="col-lg-6">
                    <div class="mb-3">
                        <label for="dire_fisica" class="form-label"><strong> Dirección fisica </strong><b style="color: red;">*</b></label>
                        <input type="text" class="form-control form-control-lg @error('Direccion') is-invalid @enderror" id="dire_fisica" name="Direccion[]" placeholder="Ejm. Cll 96 # 60-40 INT 400" required>
                    </div>
                        @error('Direccion')
                            <div class="alert alert-danger mt-1 mb-1"><small>{{ $message }}</small></div>
                        @enderror
                </div>
                <div class="col-lg-6 mb-3">
                    <label for="postal" class="form-label"> <strong> Codigo postal </strong><b style="color: red;">*</b></label>
                    <select class="form-select form-select-lg select2 @error('Codigo_postal') is-invalid @enderror" id="postal" name="Codigo_postal[]" data-placeholder="Seleccionar" required>
                        <option value="0">Seleccione</option>
                        @foreach ($codigo_postal as $key => $value)
                        <option value="{{$value['Code']}}">{{$value['Name']}} --- {{$value['U_HBT_Lugar']}}</option>
                        @endforeach
                    </select>
                        @error('Codigo_postal')
                            <div class="alert alert-danger mt-1 mb-1"><small>{{ $message }}</small></div>
                        @enderror
                </div>
                <div class="row mt-2">
                    <div class="col-lg-6 mb-3 ml-2 rounded bg-light">
                        <p class="form-label"> <strong> Desea utilizar esta dirección para facturación: </strong> <b style="color: red;">*</b></p>
                        <div class="form-check py-2">
                            <input class="form-check-input" type="radio" name="Doble_dire" id="si_dire" value="si" onclick="borar_direccion()">
                            <label class="form-check-label" for="si_dire">Si</label>
                        </div>
                        <div class="form-check py-2">
                            <input class="form-check-input" type="radio" name="Doble_dire" id="no_dire" value="no" onclick="Direccion()">
                            <label class="form-check-label" for="no_dire">No</label>
                        </div>
                        @error('Doble_dire')
                            <div class="alert alert-danger mt-1 mb-1"><small>{{ $message }}</small></div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="row" id="dir">

            </div>

            <div class="row d-flex justify-content-end mb-5">
                <div class="col-12 col-md-4 pb-3 pb-md-0 d-grid gap-2">
                    <button type="submit" class="btn btn-dark text-white">Crear</button>
                </div>
                <!-- <div class="col-md-2 col-12">
                        <a href="{{route('login')}}" class="d-grid gap-2">
                        <button type="button" class="btn btn-outline-dark">Volver</button>
                        </a>
                    </div> -->
            </div>
        </form>
    </div>
</div>
@endsection
@section('css')
<style>
    .select2 {
        width: 100% !important;

    }

    .select2-container--bootstrap-5 .select2-selection {
        min-height: 2.9rem !important;
    }

    .select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
        padding-top: 3px !important;
        font-size: 20px;
        color: rgba(102, 102, 102);
    }

    .select2-container .select2-selection--single .select2-selection__rendered {
        padding-right: 0px !important;
    }

    .select2-container--bootstrap-5 .select2-selection--single {
        background-position: right 0.75rem center !important;

    }
</style>
@endsection
@section('js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    function sortJSON(data, key, orden) {
        return data.sort(function(a, b) {
            var x = a[key],
                y = b[key];

            if (orden === 'asc') {
                return ((x < y) ? -1 : ((x > y) ? 1 : 0));
            }

            if (orden === 'desc') {
                return ((x > y) ? -1 : ((x < y) ? 1 : 0));
            }
        });
    }

    $('.select2').select2({
        theme: "bootstrap-5"
    });

    var tiposDocumento = '<?php echo json_encode($tipo_d) ?>';

    let tipos_d = JSON.parse(tiposDocumento);

    sortJSON(tipos_d, 'Name', 'asc');

    var depar = '<?php echo json_encode($departamento) ?>';

    let departamentos = JSON.parse(depar);

    sortJSON(departamentos, 'U_NomDepartamento', 'asc');

    
    var client = '<?php echo json_encode($docClient) ?>';

    let ClientExist = JSON.parse(client);

    function validar() {
        let documento = $("#Doc").val();
        console.log(documento);
        
        let incluye = ClientExist.includes(documento);
        console.log( incluye );
        if ( documento !== '' ) {
            if (incluye == true || documento == '') {
                $("#mensaje").text('');
                $("#mensaje").append(`
                    
                    <div class="alert alert-danger mt-1 mb-1"><small>Documento ya existe</small></div>
                `);
                $("#Doc").removeClass("is-valid");
                $("#Doc").addClass("is-invalid");
            } else {

                $("#mensaje").text('');
                $("#Doc").removeClass("is-invalid");
                $("#Doc").addClass("is-valid");
            }
        }else {
            $("#mensaje").text('');
            $("#mensaje").append(`
                
                <div class="alert alert-danger mt-1 mb-1"><small>Ingresar un documento</small></div>
            `);
            $("#Doc").removeClass("is-valid");
            $("#Doc").addClass("is-invalid");
        }
    }

    for (let tipo of tipos_d) {
        $('#tipo_doc').append(`
            <option value="${tipo['Code']}">${tipo['Name']}</option>
        `);
    }

    let dep = '';
    for (let depart of departamentos) {
        if (dep !== depart['U_NomDepartamento']) {
            $('#depar').append(`
                    <option value="${depart['U_NomDepartamento']}">${depart['U_NomDepartamento'].toUpperCase()}</option>
                `);
            dep = depart['U_NomDepartamento'];
        }
    }

    function Mupio() {
        $('#ciudad').text('');
        let departamentoSelected = $('#depar option:selected').val();
        console.log(departamentoSelected);

        for (let municipio of departamentos) {
            if (departamentoSelected == municipio['U_NomDepartamento']) {
                $('#ciudad').append(`
                        <option value="${municipio['Code']}">${municipio['Name']}</option>
                    `);
            }
        }
    }

    function borar_direccion() {
        $("#dir").text('');
    }

    function Direccion() {
        $("#dir").append(`
                <input type="hidden" name="Tipo_direccion[]" value="bo_BillTo">
                    <input type="hidden" name="Nombre_direccion[]" value="FACTURA">
                    <div class="col-12" style="border-bottom: solid 1px  #0000;">
                        <h5 class="text-start"> <strong> Facturación. </strong> </h5>
                    </div>
                    <hr>
                    <div class="col-6">
                        <label for="depar" class="form-label"> <strong> Departamento </strong><b style="color: red;">*</b></label>
                        <select class="form-select form-select-lg select2" id="depar2" name="Departamento[]" data-placeholder="Seleccionar" onchange="Mupio2()" required>
                            <option>Seleccione</option>
                        </select>
                    </div>
                    <div class="col-6">
                        <label for="ciudad" class="form-label"> <strong> Municipio/Ciudad </strong><b style="color: red;">*</b></label>
                        <select class="form-select form-select-lg select2" id="ciudad2" name="Ciudad[]" data-placeholder="Seleccionar" required>
                            <option>Seleccione</option>
                        </select>
                    </div>
                    <div class="col-6">
                        <div class="mb-3">
                            <label for="barrio2" class="form-label"><strong> Barrio/Vereda </strong><b style="color: red;">*</b></label>
                            <input type="text" class="form-control form-control-lg" id="barrio2" name="Barrio[]" placeholder="Ejm. Poblado" onkeyup="mayusculas_barrio2()" required>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-3">
                            <label for="dire_fisica" class="form-label"><strong> Dirección fisica </strong><b style="color: red;">*</b></label>
                            <input type="text" class="form-control form-control-lg" id="dire_fisica" name="Direccion[]" placeholder="Ejm. Cll 96 # 60-40 INT 400" required>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <label for="postal" class="form-label"> <strong> Codigo postal </strong><b style="color: red;">*</b></label>
                        <select class="form-select form-select-lg select2" id="postal" name="Codigo_postal[]" data-placeholder="Seleccionar" required>
                            <option>Seleccione</option>
                            @foreach ($codigo_postal as $key => $value)
                                <option value="{{$value['Code']}}">{{$value['Name']}} --- {{$value['U_HBT_Lugar']}}</option>
                            @endforeach
                        </select> 
                    </div>
                    <hr>
                `);

        let dep2 = '';
        for (let depart2 of departamentos) {
            if (dep2 !== depart2['U_NomDepartamento']) {
                $('#depar2').append(`
                        <option value="${depart2['U_NomDepartamento']}">${depart2['U_NomDepartamento'].toUpperCase()}</option>
                    `);
                dep2 = depart2['U_NomDepartamento'];
            }
        }
    }

    function Mupio2() {
        $('#ciudad2').text('');
        let departamentoSelected2 = $('#depar2 option:selected').val();
        console.log();
        for (let municipio2 of departamentos) {
            if (departamentoSelected2 == municipio2['U_NomDepartamento']) {
                $('#ciudad2').append(`
                        <option value="${municipio2['Code']}">${municipio2['Name']}</option>
                    `);
            }
        }
    }

    function archivos() {
        let tipos = $("#tipo_doc option:selected").val();
        if (tipos == 31) {
            $("#archi").text('');
            $("#archi2").text('');
            $("#archi").append(`
                        <label class="form-label" for="rut"> <strong> Rut* </strong> </label>
                        <div class="input-group input-group-lg mb-3">
                            <input type="file" class="form-control" id="rut" required name="Documento_idetidad[]">
                        </div>
                    `);
        } else {
            $("#archi2").text('');
            $("#archi").text('');
            $("#archi2").append(`
                        <label class="form-label" for="cc_nit"> <strong> Nit/Cedula Ciudadania </strong> </label>
                        <div class="input-group input-group-lg mb-3">
                            <input type="file" class="form-control" id="cc_nit" name="Documento_idetidad[]">
                        </div>
                    `);
        }
    }

    function dcto() {
        let seg = $("#segmento option:selected").text();
        if (seg == "Aves - Engorde" || seg == "Aves - Ponedoras" || seg == "Otros") {
            $("#descuento").val(5);
        } else if (seg == "Agropecuaria" || seg == "Clinica veterinaria" || seg == "Equinos - Criadero" || seg == "Equinos - Pesebrera" || seg == "Mascotas - Pet shop" || seg == "Veterinario a domicilio") {
            $("#descuento").val(6);
        } else if (seg == "Ganaderia - Carne" || seg == "Ganaderia - Doble proposito" || seg == "Ganaderia - Leche" || seg == "Porcicultura - Ceba" || seg == "Porcicultura - Ciclo completo" || seg == "Porcicultura - Cria" || seg == "Porcicultura - Levante") {
            $("#descuento").val(8);
        } else {
            $("#descuento").val(0);
        }
    }

    function archivos2() {
        $("#archi").text('');
        $("#archi").append(`
                        <label class="form-label" for="rut"> <strong> Rut* </strong> </label>
                        <div class="input-group input-group-lg mb-3">
                            <input type="file" class="form-control" id="rut" required name="Documento_idetidad[]">
                        </div>
                    `);
    }

    function borrararch2() {
        $("#archi label").text('');
        $("#archi label").append(` <strong> Rut </strong> `);
        $("#rut").removeAttr('required');
    }

    function mayusculas() {
        let x = $('#nane').val();
        $('#nane').val(x.toUpperCase());
    }

    function mayusculas_barrio() {
        let x = $('#barrio').val();
        $('#barrio').val(x.toUpperCase());
    }

    function mayusculas_barrio2() {
        let x = $('#barrio2').val();
        $('#barrio2').val(x.toUpperCase());
    }

    // function Valid_email() {
    //     let correo = $('#correo_fac').val();
    //     console.log(correo);

    //     var myHeaders = new Headers();
    //     myHeaders.append("apikey", "l8Cq3sBkmZUgExqcXA7SzxS23h6eOJ24");

    //     var requestOptions = {
    //     method: 'GET',
    //     redirect: 'follow',
    //     headers: myHeaders
    //     };

    //     fetch("https://api.apilayer.com/email_verification/check?email="+correo, requestOptions)
    //     .then(response => response.text())
    //     .then(result => 
    //         {
    //             let resp = JSON.parse(result)
    //             console.log(resp);
    //             if (resp['smtp_check']) {
    //                 console.log('si');
    //                 $("#correo_fac").removeClass('is-invalid');
    //                 $("#correo_fac").addClass('is-valid');
    //                 $("#mensaje1").text('');
    //             }else{
    //                 console.log('no');
    //                 $("#mensaje1").text('');
    //                 $("#correo_fac").removeClass('is-valid');
    //                 $("#correo_fac").addClass('is-invalid');
    //                 $("#mensaje1").append(`
                    
    //                 <div class="alert alert-danger" role="alert">
    //                     Correo no valido
    //                 </div>`)
    //             }
    //         }
    //     )
    //     .catch(error => console.log('error', error));
        
    //     var myHeaders = new Headers();
    //         myHeaders.append("Cookie", "emaillistverify_res=6n9g3hjpj9rik75j5jgcs4ui11");

    //         var requestOptions = {
    //         method: 'GET',
    //         headers: myHeaders,
    //         redirect: 'follow'
    //         };

    //         fetch("https://app.verificaremails.com/api/verifyEmail?secret=VuKx3XigoPHddVrVFhFKb&email="+correo, requestOptions)
    //         .then(response => response.text())
    //         .then(result => {
    //             if (result == 'ok') {
    //                 console.log('si');
    //                 $("#correo_fac").removeClass('is-invalid');
    //                 $("#correo_fac").addClass('is-valid');
    //                 $("#mensaje1").text('');
    //             }else{
    //                 console.log('no');
    //                 $("#mensaje1").text('');
    //                 $("#correo_fac").removeClass('is-valid');
    //                 $("#correo_fac").addClass('is-invalid');
    //                 $("#mensaje1").append(`
                    
    //                 <div class="alert alert-danger" role="alert">
    //                     Correo no valido
    //                 </div>`)
    //             }
    //         })
    //         .catch(error => console.log('error', error));

    // }
    
    // function Valid_email2() {
    //     let correo = $('#correo_noti').val();
    //     console.log(correo);
        
        
    //     var myHeaders = new Headers();
    //         myHeaders.append("Cookie", "emaillistverify_res=6n9g3hjpj9rik75j5jgcs4ui11");

    //         var requestOptions = {
    //         method: 'GET',
    //         headers: myHeaders,
    //         redirect: 'follow'
    //         };

    //         fetch("https://app.verificaremails.com/api/verifyEmail?secret=VuKx3XigoPHddVrVFhFKb&email="+correo, requestOptions)
    //         .then(response => response.text())
    //         .then(result => {
    //             if (result == 'ok') {
    //                 console.log('si');
    //                 $("#correo_noti").removeClass('is-invalid');
    //                 $("#correo_noti").addClass('is-valid');
    //                 $("#mensaje2").text('');
    //             }else{
    //                 console.log('no');
    //                 $("#mensaje2").text('');
    //                 $("#correo_noti").removeClass('is-valid');
    //                 $("#correo_noti").addClass('is-invalid');
    //                 $("#mensaje2").append(`
                    
    //                 <div class="alert alert-danger" role="alert">
    //                     Correo no valido
    //                 </div>`)
    //             }
    //         })
    //         .catch(error => console.log('error', error));

    // }
</script>

@endsection