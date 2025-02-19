<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class LoginModel extends Model
{
    protected $table = 'usuario';
    public $timestamp = false;

    public function buscarUsuario($usuario, $contraseña)
    {
        $usuarioObtenido = $this->where('usuario', $usuario)->first();

        if (!$usuarioObtenido || !Hash::check($contraseña, $usuarioObtenido->contraseña)) {
            return null;
        }

        return $usuarioObtenido;
    }

    public function obtenerUsuarioPorId($id)
    {
        return $this->find($id);
    }
}