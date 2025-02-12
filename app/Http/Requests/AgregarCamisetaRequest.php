<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AgregarCamisetaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'precio' => 'required|numeric',
            'estado' => 'required|integer',
            'imagen' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'cantidadXS' => 'nullable|integer|min:0',
            'cantidadS' => 'nullable|integer|min:0',
            'cantidadM' => 'nullable|integer|min:0',
            'cantidadL' => 'nullable|integer|min:0',
            'cantidadXL' => 'nullable|integer|min:0',
            'cantidadXXL' => 'nullable|integer|min:0'
        ];
    }

    public function messages()
    {
        return [
            'imagen.image' => 'El archivo que subiste no es una imagen. Por favor, selecciona una imagen válida (JPEG, PNG o JPG).',
            'imagen.mimes' => 'La imagen debe ser de tipo JPEG, PNG o JPG.',
            'imagen.max' => 'La imagen no puede ser mayor a 2MB. Por favor, sube una imagen más pequeña.'
        ];
    }
}
