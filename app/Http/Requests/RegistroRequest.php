<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegistroRequest extends FormRequest
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
            'usuario' => 'required|string|max:255|unique:usuario,usuario',
            'email' => 'required|email|max:255|unique:usuario,email',
            'contraseña' => 'required|string|min:8'
        ];
    }

    public function messages()
    {
        return [
            'contraseña.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'usuario.unique' => 'El nombre de usuario ya está en uso.',
            'email.unique' => 'El correo electrónico ya está registrado.',
        ];
    }
}
