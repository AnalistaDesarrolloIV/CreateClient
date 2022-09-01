<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class cliente extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'TipoDocumento' => 'required',
            'Documento' => 'required|numeric',
            'Nombre' => 'required|min:3|max:50|regex:/^[\pL\s\-]+$/u',
            'Telefono' => 'required|min:10',
            'Segmento' => 'required',
            'Descuento' => 'required|numeric',
            'Facturacion' => 'required|email',
            'Notificaciones' => 'required|email',
            'Comentarios' => 'max:100',
            'Res_Iva' => 'required',
            "Departamento"=>"required",
            "Ciudad"=>"required",
            "Barrio"=>"required",
            "Direccion"=>"required",
            "Codigo_postal"=>"required",
            "Doble_dire"=>"required"
        ];
    }

    public function attributes()
    {
        return [
            'Facturacion' => 'Correo facturación',
            'Notificaciones' => 'Corro notificación',
            'Res_Iva' => 'Responsable de iva',
            "Direccion"=>"Dirección",
            "Codigo_postal"=>"Codigo postal",
            "Doble_dire"=>"Direccion de factiracion Si/No"
        ];
    }
}
