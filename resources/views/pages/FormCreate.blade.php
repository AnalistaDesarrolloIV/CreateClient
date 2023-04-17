@extends('welcome')

@section('tittle', 'Crear cliente')

@section('content')
    <div class="row justify-content-center mt-5" id="cont">
        <div class=" col-12 col-lg-10 contenedor rounded p-4">
            <form action="{{ route('store') }}" method="post" enctype="multipart/form-data" id="FormCreate">
                @csrf
                <div class="row">
                    <div class="col-12">
                        <h3 class="text-center" style="font-size: 35px;"> <strong> Información Personal. </strong> </h3>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="tipo_doc" class="form-label"> <strong> Tipo documento <b style="color: red;">*</b>
                                </strong></label>
                            <select class="form-select form-select-lg select2  @error('TipoDocumento') is-invalid @enderror"
                                id="tipo_doc" name="TipoDocumento" data-placeholder="Seleccionar">
                                <option value=''>Seleccione</option>
                            </select>
                        </div>
                        @error('TipoDocumento')
                            <div class="alert alert-danger mt-1 mb-1"><small>{{ $message }}</small></div>
                        @enderror
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="Doc" class="form-label"><strong> N° documento <b style="color: red;">*</b>
                                </strong></label>
                            <input type="text"
                                class="form-control form-control-lg @error('Documento') is-invalid @enderror" id="Doc"
                                value="{{ old('Documento') }}" name="Documento" placeholder="Ejm. 1005687427"
                                onchange="validar()">
                        </div>
                        <div id="mensaje">

                        </div>
                        @error('Documento')
                            <div class="alert alert-danger mt-1 mb-1"><small>{{ $message }}</small></div>
                        @enderror
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="nane" class="form-label"><strong> Nombre completo <b style="color: red;">*</b>
                                </strong></label>
                            <input type="text" class="form-control form-control-lg @error('Nombre') is-invalid @enderror"
                                id="nane" name="Nombre" value="{{ old('Nombre') }}" placeholder="Ejm. IVAN MONTES"
                                onkeyup="mayusculas()">
                        </div>
                        @error('Nombre')
                            <div class="alert alert-danger mt-1 mb-1"><small>{{ $message }}</small></div>
                        @enderror
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="tel" class="form-label"><strong> Teléfono/Celular <b style="color: red;">*</b>
                                </strong></label>
                            <input type="number"
                                class="form-control form-control-lg @error('Telefono') is-invalid @enderror" id="tel"
                                name="Telefono" value="{{ old('Telefono') }}" placeholder="Ejm. 325698412">
                        </div>
                        @error('Telefono')
                            <div class="alert alert-danger mt-1 mb-1"><small>{{ $message }}</small></div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="segmento" class="form-label"> <strong> Segmento <b style="color: red;">*</b>
                                </strong></label>
                            <select class="form-select form-select-lg select2 @error('Segmento') is-invalid @enderror"
                                id="segmento" name="Segmento" data-placeholder="Seleccionar">
                                <option value=''>Seleccione</option>
                                @foreach ($Getsegmentos as $key => $seg)
                                    <option value="{{$seg['SEGMENTO']}}">{{$seg['SEGMENTO']}}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('Segmento')
                            <div class="alert alert-danger mt-1 mb-1"><small>{{ $message }}</small></div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="grupos" class="form-label"> <strong> Grupo <b style="color: red;">*</b>
                                </strong></label>
                            <select class="form-select form-select-lg select2 @error('grupos') is-invalid @enderror"
                                id="grupo" name="grupos" data-placeholder="Seleccionar">
                                <option value=''>Seleccione</option>
                                @foreach ($Getgrupos as $key => $valor)
                                    <option value='{{ $valor['GroupCode'] }}--{{ $valor['GroupName'] }}'>
                                        {{ $valor['GroupName'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('Segmento')
                            <div class="alert alert-danger mt-1 mb-1"><small>{{ $message }}</small></div>
                        @enderror
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="correo_fac" class="form-label"><strong> Correo Facturación <b
                                        style="color: red;">*</b> </strong></label>
                            <input type="email"
                                class="form-control form-control-lg @error('Facturacion') is-invalid @enderror"
                                id="correo_fac" name="Facturacion" value="{{ old('Facturacion') }}"
                                {{-- onchange="Valid_email()" --}} placeholder="Ejm. factura@example.com">
                        </div>

                        {{-- <div id="mensaje1">
                        
                        </div> --}}

                        @error('Facturacion')
                            <div class="alert alert-danger mt-1 mb-1"><small>{{ $message }}</small></div>
                        @enderror
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="correo_noti" class="form-label"><strong> Correo Notificaciones <b
                                        style="color: red;">*</b> </strong></label>
                            <input type="email"
                                class="form-control form-control-lg @error('Notificaciones') is-invalid @enderror"
                                id="correo_noti" name="Notificaciones" value="{{ old('Notificaciones') }}"
                                {{-- onchange="Valid_email2()" --}} placeholder="Ejm. Notificacion@example.com">
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
                            <textarea class="form-control @error('Comentarios') is-invalid @enderror" id="coments" name="Comentarios"
                                rows="3" placeholder="Ejm. QUIERO OTRA DIRECCIÓN">{{ old('Comentarios') }}</textarea>
                        </div>
                        @error('Comentarios')
                            <div class="alert alert-danger mt-1 mb-1"><small>{{ $message }}</small></div>
                        @enderror
                    </div>

                    <div class="row">
                        <label for="" class="form-label"><b> IVA </b><b style="color: red;">*</b></label>
                        <div class="col-12 mb-3 ml-5 rounded bg-light">
                            <p class="form-label"> <strong>¿Es responsable de IVA? </strong> </p>
                            <div class="form-check py-2">
                                <input class="form-check-input" type="radio" name="Res_Iva" id="si"
                                    value="si" onclick="si_iva()">
                                <label class="form-check-label" for="si">Si</label>
                            </div>
                            <div class="form-check py-2">
                                <input class="form-check-input" type="radio" name="Res_Iva" id="no"
                                    value="no" onclick="no_iva()">
                                <label class="form-check-label" for="no">No</label>
                            </div>
                            @error('Res_Iva')
                                <div class="alert alert-danger mt-1 mb-1"><small>{{ $message }}</small></div>
                            @enderror
                        </div>
                    </div>

                    <div class="row d-none" id="archi1">
                        <label for="" class="form-label"><b> Documentos </b></label>

                        <div class="col-5">
                            <div class="row">
                                
                                <label for="name_doc" class="col-sm-2 col-form-label">Nombre</label>
                                <div class="col-sm-10">
                                    <input type="text" placeholder="Ejem. CC" class="form-control" name="NameDoc[]" id="name_doc">
                                </div>
                                <!-- <div class="col-2">
                                    <label class="form-label" for="rut"> <strong> Nombre </strong></label>
                                </div>
                                <div class="col-10">
                                    <input type="text" class="form-control" name="NameDoc[]" id="name_doc">
                                </div> -->
                            </div>
                        </div>
                        <div class="col-5">
                            <div class="row">
                                <label for="rut" class="col-sm-2 col-form-label">Archivo</label>
                                <div class="col-sm-10">
                                    <input type="file" class="form-control" id="rut" name="Documento_idetidad[]" accept=".pdf, .jpg, .png, .jpeg">
                                </div>

                                <!-- <div class="col-2">
                                    <label class="form-label" for="rut"> <strong> Archivo </strong></label>
                                </div>
                                <div class="col-10">
                                    <div class="input-group input-group-lg mb-3">
                                        <input type="file" class="form-control form-control-sm" id="rut" required
                                            name="Documento_idetidad[]" accept=".pdf, .jpg, .png, .jpeg">
                                    </div>
                                </div> -->
                            </div>
                        </div>
                        <div class="col-2">
                            <button type="button" onclick="agarch_1()" class="btn btn-success">+</button>
                        </div>
                        <!-- <div class="col-12">
                            <label class="form-label" for="cc_nit"> <strong> Nit/Cedula Ciudadania </strong> </label>
                            <div class="input-group input-group-lg mb-3">
                                <input type="file" class="form-control" id="cc_nit" name="Documento_idetidad[]"
                                    accept=".pdf, .jpg, .png, .jpeg">
                            </div>
                        </div> -->
                    </div>

                    <div class="row d-none" id="archi2">
                        <label for="" class="form-label"><b> Documentos </b><b style="color: red;">*</b></label>
                        
                        <div class="col-5">
                            <div class="row">
                                
                                <label for="name_doc" class="col-sm-2 col-form-label">Nombre</label>
                                <div class="col-sm-10">
                                    <input type="text" placeholder="Ejem. CC" class="form-control" name="NameDoc[]" id="name_doc">
                                </div>
                                <!-- <div class="col-2">
                                    <label class="form-label" for="rut"> <strong> Nombre </strong></label>
                                </div>
                                <div class="col-10">
                                    <input type="text" class="form-control" name="NameDoc[]" id="name_doc">
                                </div> -->
                            </div>
                        </div>
                        <div class="col-5">
                            <div class="row">
                                <label for="rut" class="col-sm-2 col-form-label">Archivo</label>
                                <div class="col-sm-10">
                                    <input type="file" class="form-control" id="rut" required
                                        name="Documento_idetidad[]" accept=".pdf, .jpg, .png, .jpeg">
                                </div>

                                <!-- <div class="col-2">
                                    <label class="form-label" for="rut"> <strong> Archivo </strong></label>
                                </div>
                                <div class="col-10">
                                    <div class="input-group input-group-lg mb-3">
                                        <input type="file" class="form-control form-control-sm" id="rut" required
                                            name="Documento_idetidad[]" accept=".pdf, .jpg, .png, .jpeg">
                                    </div>
                                </div> -->
                            </div>
                        </div>
                        <div class="col-2">
                            <button type="button" onclick="agarch_2()" class="btn btn-success">+</button>
                        </div>
                        <!-- <div class="col-12">
                            <label class="form-label" for="cc_nit"> <strong> Nit/Cedula Ciudadania </strong><b
                                    style="color: red;">*</b> </label>
                            <div class="input-group input-group-lg mb-3">
                                <input type="file" class="form-control" id="cc_nit" required
                                    name="Documento_idetidad[]" accept=".pdf, .jpg, .png, .jpeg">
                            </div>
                        </div> -->
                    </div>
                    <div class="row d-none" id="archi3">
                        <label for="" class="form-label"><b> Documentos </b><b style="color: red;">*</b></label>

                        <div class="col-5">
                            <div class="row">
                                
                                <label for="name_doc" class="col-sm-2 col-form-label">Nombre</label>
                                <div class="col-sm-10">
                                    <input type="text" placeholder="Ejem. CC" class="form-control" name="NameDoc[]" id="name_doc">
                                </div>
                                <!-- <div class="col-2">
                                    <label class="form-label" for="rut"> <strong> Nombre </strong></label>
                                </div>
                                <div class="col-10">
                                    <input type="text" class="form-control" name="NameDoc[]" id="name_doc">
                                </div> -->
                            </div>
                        </div>
                        <div class="col-5">
                            <div class="row">
                                <label for="rut" class="col-sm-2 col-form-label">Archivo</label>
                                <div class="col-sm-10">
                                    <input type="file" class="form-control" id="rut" required
                                        name="Documento_idetidad[]" accept=".pdf, .jpg, .png, .jpeg">
                                </div>

                                <!-- <div class="col-2">
                                    <label class="form-label" for="rut"> <strong> Archivo </strong></label>
                                </div>
                                <div class="col-10">
                                    <div class="input-group input-group-lg mb-3">
                                        <input type="file" class="form-control form-control-sm" id="rut" required
                                            name="Documento_idetidad[]" accept=".pdf, .jpg, .png, .jpeg">
                                    </div>
                                </div> -->
                            </div>
                        </div>
                        <div class="col-2">
                            <button type="button" onclick="agarch_3()" class="btn btn-success">+</button>
                        </div>
                    </div>

                    <div class="col"></div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-12">
                        <h2 class="text-center" style="font-size: 35px;"> <strong> Direcciones. </strong> </h2>
                    </div>
                    <div class="col-12">
                        <h3 class="text-start"> <strong> Dirección de envío. </strong> </h3>
                    </div>
                    <hr>
                    <input type="hidden" name="Tipo_direccion[]" value="bo_ShipTo">
                    <input type="hidden" name="Nombre_direccion[]" value="ENVIO">
                    <div class="col-12" id="alert_cirecciones">

                    </div>
                    <div class="col-lg-6">
                        <label for="depar" class="form-label"> <strong> Departamento </strong><b
                                style="color: red;">*</b></label>
                        <select class="form-select form-select-lg select2 @error('Departamento') is-invalid @enderror"
                            id="depar" name="Departamento[]" data-placeholder="Seleccionar" onchange="Mupio()"
                            required>
                            <option value=''>Seleccione</option>
                        </select>
                        @error('Departamento')
                            <div class="alert alert-danger mt-1 mb-1"><small>{{ $message }}</small></div>
                        @enderror
                    </div>
                    <div class="col-lg-6">
                        <label for="ciudad" class="form-label"> <strong> Municipio/Ciudad </strong><b
                                style="color: red;">*</b></label>
                        <select class="form-select form-select-lg select2 @error('Ciudad') is-invalid @enderror"
                            id="ciudad" name="Ciudad[]" data-placeholder="Seleccionar" required>
                            <option value=''>Seleccione</option>
                        </select>
                        @error('Ciudad')
                            <div class="alert alert-danger mt-1 mb-1"><small>{{ $message }}</small></div>
                        @enderror
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="barrio" class="form-label"><strong> Barrio/Vereda </strong><b
                                    style="color: red;">*</b></label>
                            <input type="text"
                                class="form-control form-control-lg @error('Barrio') is-invalid @enderror" id="barrio"
                                name="Barrio[]" placeholder="Ejm. Poblado" onkeyup="mayusculas_barrio()" required>
                        </div>
                        @error('Barrio')
                            <div class="alert alert-danger mt-1 mb-1"><small>{{ $message }}</small></div>
                        @enderror
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="dire_fisica" class="form-label"><strong> Dirección fisica </strong><b
                                    style="color: red;">*</b></label>
                            <input type="text"
                                class="form-control form-control-lg @error('Direccion') is-invalid @enderror"
                                id="dire_fisica" name="Direccion[]" placeholder="Ejm. Cll 96 # 60-40 INT 400" required>
                        </div>
                        @error('Direccion')
                            <div class="alert alert-danger mt-1 mb-1"><small>{{ $message }}</small></div>
                        @enderror
                    </div>
                    <div class="col-lg-6 mb-3">
                        <label for="postal" class="form-label"> <strong> Código postal </strong><b
                                style="color: red;">*</b></label>
                        <select class="form-select form-select-lg select2 @error('Codigo_postal') is-invalid @enderror"
                            id="postal" name="Codigo_postal[]" data-placeholder="Seleccionar" required>
                            <option value="0">Seleccione</option>
                            @foreach ($codigo_postal as $key => $value)
                                <option value="{{ $value['Code'] }}">{{ $value['Name'] }} ---
                                    {{ $value['U_HBT_Lugar'] }}</option>
                            @endforeach
                        </select>
                        @error('Codigo_postal')
                            <div class="alert alert-danger mt-1 mb-1"><small>{{ $message }}</small></div>
                        @enderror
                    </div>
                    <div class="row mt-2">
                        <div class="col-lg-6 mb-3 ml-2 rounded bg-light">
                            <p class="form-label"> <strong> ¿Desea utilizar esta dirección para facturación? </strong> <b
                                    style="color: red;">*</b></p>
                            <div class="form-check py-2">
                                <input class="form-check-input" type="radio" name="Doble_dire" id="si_dire"
                                    value="si" onclick="borar_direccion()">
                                <label class="form-check-label" for="si_dire">Si</label>
                            </div>
                            <div class="form-check py-2">
                                <input class="form-check-input" type="radio" name="Doble_dire" id="no_dire"
                                    value="no" onclick="Direccion()">
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
                        <button type="button" class="btn btn-dark text-white" id="btnCreate"
                            onclick="Crear()">Crear</button>
                    </div>
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

        var tiposDocumento = '<?php echo json_encode($tipo_d); ?>';
        let tipos_d = JSON.parse(tiposDocumento);
        sortJSON(tipos_d, 'Name', 'asc');

        var depar = '<?php echo json_encode($departamento); ?>';
        let departamentos = JSON.parse(depar);
        sortJSON(departamentos, 'U_NomDepartamento', 'asc');

        var client = '<?php echo json_encode($docClient); ?>';
        let ClientExist = JSON.parse(client);


        function validar() {
            let documento = $("#Doc").val();


            let incluye = ClientExist.includes(documento);

            if (documento !== '') {
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
            } else {
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
            console.log(depart);
            if (dep !== depart['U_NomDepartamento']) {
                $('#depar').append(`
                    <option value="${depart['U_NomDepartamento']}">${depart['U_NomDepartamento']}</option>
                `);
                dep = depart['U_NomDepartamento'];
            }
        }

        function Mupio() {
            $('#ciudad').text('');
            let departamentoSelected = $('#depar option:selected').val();


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
                        <h3 class="text-start"> <strong> Dirección de Facturación. </strong> </h3>
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
                                <option value="{{ $value['Code'] }}">{{ $value['Name'] }} --- {{ $value['U_HBT_Lugar'] }}</option>
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

            for (let municipio2 of departamentos) {
                if (departamentoSelected2 == municipio2['U_NomDepartamento']) {
                    $('#ciudad2').append(`
                        <option value="${municipio2['Code']}">${municipio2['Name']}</option>
                    `);
                }
            }
        }

        function si_iva() {
            let doc = $("#tipo_doc option:selected").val();
            console.log("documento: " + doc);
            if (doc == 31) {
                $("#archi1").addClass('d-none');
                $("#archi2").addClass('d-none');
                $("#archi3").removeClass('d-none');
            } else {
                $("#archi1").addClass('d-none');
                $("#archi3").addClass('d-none');
                $("#archi2").removeClass('d-none');
            }
        }

        function no_iva() {
            let doc = $("#tipo_doc option:selected").val();
            console.log("documento: " + doc);

            if (doc == 31) {
                $("#archi1").addClass('d-none');
                $("#archi2").addClass('d-none');
                $("#archi3").removeClass('d-none');
            } else {
                $("#archi2").addClass('d-none');
                $("#archi3").addClass('d-none');
                $("#archi1").removeClass('d-none');
            }
        }


        function agarch_1() {
            // this->addClass('disabled');
            $("#archi1").append(`
                <div class="col-5 mt-2">
                    <div class="row">
                        
                        <label for="name_doc" class="col-sm-2 col-form-label">Nombre</label>
                        <div class="col-sm-10">
                            <input type="text" placeholder="Ejem. CC" class="form-control" name="NameDoc[]" id="name_doc">
                        </div>
                    </div>
                </div>
                <div class="col-5 mt-2">
                    <div class="row">
                        <label for="rut" class="col-sm-2 col-form-label">Archivo</label>
                        <div class="col-sm-10">
                            <input type="file" class="form-control" id="rut" required
                                name="Documento_idetidad[]" accept=".pdf, .jpg, .png, .jpeg">
                        </div>

                        <!-- <div class="col-2">
                            <label class="form-label" for="rut"> <strong> Archivo </strong></label>
                        </div>
                        <div class="col-10">
                            <div class="input-group input-group-lg mb-3">
                                <input type="file" class="form-control form-control-sm" id="rut" required
                                    name="Documento_idetidad[]" accept=".pdf, .jpg, .png, .jpeg">
                            </div>
                        </div> -->
                    </div>
                </div>
                <div class="col-2 mt-2">
                    <button type="button" onclick="agarch_1()" class="btn btn-success">+</button>
                </div>
            `);
        }

        function agarch_2() {
            // this->addClass('disabled');
            $("#archi2").append(`
                <div class="col-5 mt-2">
                    <div class="row">
                        
                        <label for="name_doc" class="col-sm-2 col-form-label">Nombre</label>
                        <div class="col-sm-10">
                            <input type="text" placeholder="Ejem. CC" class="form-control" name="NameDoc[]" id="name_doc">
                        </div>
                    </div>
                </div>
                <div class="col-5 mt-2">
                    <div class="row">
                        <label for="rut" class="col-sm-2 col-form-label">Archivo</label>
                        <div class="col-sm-10">
                            <input type="file" class="form-control" id="rut" required
                                name="Documento_idetidad[]" accept=".pdf, .jpg, .png, .jpeg">
                        </div>

                        <!-- <div class="col-2">
                            <label class="form-label" for="rut"> <strong> Archivo </strong></label>
                        </div>
                        <div class="col-10">
                            <div class="input-group input-group-lg mb-3">
                                <input type="file" class="form-control form-control-sm" id="rut" required
                                    name="Documento_idetidad[]" accept=".pdf, .jpg, .png, .jpeg">
                            </div>
                        </div> -->
                    </div>
                </div>
                <div class="col-2 mt-2">
                    <button type="button" onclick="agarch_2()" class="btn btn-success">+</button>
                </div>
            `);
        }

        function agarch_3() {
            // this->addClass('disabled');
            $("#archi3").append(`
                <div class="col-5 mt-2">
                    <div class="row">
                        
                        <label for="name_doc" class="col-sm-2 col-form-label">Nombre</label>
                        <div class="col-sm-10">
                            <input type="text" placeholder="Ejem. CC" class="form-control" name="NameDoc[]" id="name_doc">
                        </div>
                    </div>
                </div>
                <div class="col-5 mt-2">
                    <div class="row">
                        <label for="rut" class="col-sm-2 col-form-label">Archivo</label>
                        <div class="col-sm-10">
                            <input type="file" class="form-control" id="rut" required
                                name="Documento_idetidad[]" accept=".pdf, .jpg, .png, .jpeg">
                        </div>

                        <!-- <div class="col-2">
                            <label class="form-label" for="rut"> <strong> Archivo </strong></label>
                        </div>
                        <div class="col-10">
                            <div class="input-group input-group-lg mb-3">
                                <input type="file" class="form-control form-control-sm" id="rut" required
                                    name="Documento_idetidad[]" accept=".pdf, .jpg, .png, .jpeg">
                            </div>
                        </div> -->
                    </div>
                </div>
                <div class="col-2 mt-2">
                    <button type="button" onclick="agarch_3()" class="btn btn-success">+</button>
                </div>
            `);
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

        function Crear() {
            let Dep = $("#depar").val();
            let City = $("#ciudad").val();
            let Barrio = $("#barrio").val();
            let dir_f = $("#dire_fisica").val();
            let Cod_p = $("#postal").val();
            let no = $("#no_dire").val();
            let si = $("#si_dire").val();


            if (Dep == '' || City == '' || Barrio == '' || dir_f == '' || Cod_p == '' || no == null || si == null) {

                var posicion = $("#alert_cirecciones").offset().top;
                console.log(posicion);
                $("html, body").animate({
                    scrollTop: posicion - 100
                }, 500);

                $("#alert_cirecciones").html(`
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <b><i class="fas fa-exclamation-triangle"></i></b> Por favor, completar todos los campos de dirección.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `);
            } else {
                $("#FormCreate").submit();
                $("#btnCreate").prop("disabled", true);

                $("#btnCreate").html(
                    `<span class="spinner-border spinner-border-sm"
                    role="status" aria-hidden="true"></span> Creando...`
                );
                
                $("#load").click();

                $("#cont").html('');
            }
        }
    </script>

@endsection
