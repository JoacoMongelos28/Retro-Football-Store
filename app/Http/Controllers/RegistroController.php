<?php

namespace App\Http\Controllers;

use App\Models\RegistroModel;
use App\Http\Requests\RegistroRequest;

class RegistroController extends Controller
{

    private $registroModel;

    public function __construct() {
        $this->registroModel = new RegistroModel();
    }

    public function registro() {
        return view('registro');
    }

    public function registrarUsuario(RegistroRequest $request) {
        $nombre = $request->input('nombre');
        $usuario = $request->input('usuario');
        $email = $request->input('email');
        $contraseña = $request->input('contraseña');

        $resultado = $this->registroModel->registrarUsuario($nombre, $usuario, $email, $contraseña);

        if (!$resultado) {
            return redirect('registro')->with('error', 'Error al registrar el usuario');
        }

        return redirect('login')->with('exitoso', 'Usuario registrado correctamente');
    }
}