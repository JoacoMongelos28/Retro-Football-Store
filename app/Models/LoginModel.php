<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoginModel extends Model
{
    protected $table = 'usuario';
    public $timestamps = false;

    public function buscarUsuario($usuario, $contraseña)
    {
        return $this->where('usuario', $usuario)->where('contraseña', $contraseña)->first();
    }

    public function obtenerUsuarioPorId($id)
    {
        return $this->where('id', $id)->first();
    }
}
