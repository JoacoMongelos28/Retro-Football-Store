<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegistroModel extends Model
{
    protected $table = 'usuario';
    public $timestamps = false;

    public function registrarUsuario($nombre, $usuario, $email, $contraseña) {
        $usuarioNuevo = new RegistroModel();
        $usuarioNuevo->nombre = $nombre;
        $usuarioNuevo->usuario = $usuario;
        $usuarioNuevo->email = $email;
        $usuarioNuevo->contraseña = $contraseña; //password_hash($password, PASSWORD_DEFAULT);
        $usuarioNuevo->tipo_usuario = 2;

        return $usuarioNuevo->save();
    }
}