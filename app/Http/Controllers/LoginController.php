<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LoginModel;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{

    protected LoginModel $loginModel;

    public function __construct() {
        $this->loginModel = new LoginModel();
    }

    public function login() {
        if (session('usuarioId') != null) {
            return redirect('home');
        }

        return view("login");
    }    

    public function validar(Request $request) {
        $data = $this->validarDatosDelLogin($request);

        if ($data->fails()) {
            return redirect('login')->withErrors($data)->withInput();
        }

        $usuario = $request->usuario;
        $contraseña = $request->contraseña;

        $usuarioObtenido = $this->loginModel->buscarUsuario($usuario, $contraseña);

        $redirectUrl = $request->input('redirect');

        if ($usuarioObtenido != null && $usuarioObtenido->tipo_usuario == '2' && $redirectUrl) {
            session(['usuarioId' => $usuarioObtenido->id]);
            return redirect($redirectUrl);
        } else if ($usuarioObtenido != null && $usuarioObtenido->tipo_usuario == '2') {
            session(['usuarioId' => $usuarioObtenido->id]);
            return redirect('home');
        } else if ($usuarioObtenido != null && $usuarioObtenido->tipo_usuario == '1') {
            session(['usuarioId' => $usuarioObtenido->id]);
            return redirect('admin');
        } else {
            return redirect('login')->with('error', 'Usuario o contraseña incorrectos')->withInput();
        }
    }

    private function validarDatosDelLogin (Request $request) {
        return Validator::make($request->all(), [
            'usuario' => 'required|string',
            'contraseña' => 'required|string'
        ], [
            'usuario.required' => 'El usuario es requerido',
            'contraseña.required' => 'La contraseña es requerida'
        ]);
    }

    public function cerrarSesion() {
        session()->forget('usuarioId');
        return redirect('home');
    }
}