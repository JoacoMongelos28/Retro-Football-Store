<?php

namespace App\Http\Controllers;

use App\Models\LoginModel;
use App\Http\Requests\LoginRequest;
use Exception;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{

    protected LoginModel $loginModel;

    public function __construct()
    {
        $this->loginModel = new LoginModel();
    }

    public function login()
    {
        if (session('usuarioId') != null) {
            return redirect('home');
        }

        return view("login");
    }

    public function validar(LoginRequest $request)
    {
        try {
            $usuario = $request->usuario;
            $contraseña = $request->contraseña;

            $usuarioObtenido = $this->loginModel->buscarUsuario($usuario, $contraseña);

            $redirectUrl = $request->input('redirect');

            if ($usuarioObtenido != null) {
                session(['usuarioId' => $usuarioObtenido->id]);

                if ($usuarioObtenido->tipo_usuario == '2') {
                    return $redirectUrl ? redirect($redirectUrl) : redirect('home');
                } else if ($usuarioObtenido->tipo_usuario == '1') {
                    return redirect('admin');
                }
            }

            return redirect('login')->with('error', 'Usuario o contraseña incorrectos')->withInput();
        } catch (Exception $e) {
            Log::error('Error al intentar validar el usuario: ' . $e->getMessage());
            return redirect('login')->with('error', 'Hubo un error al iniciar sesión')->withInput();
        }
    }

    public function cerrarSesion()
    {
        session()->forget('usuarioId');
        return redirect('home');
    }
}