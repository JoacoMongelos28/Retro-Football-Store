<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class RegistroModel extends Model
{
    protected $table = 'usuario';
    public $fillable = ['nombre', 'usuario', 'email', 'contraseña', 'tipo_usuario'];
    public $timestamps = false;

    public function registrarUsuario($nombre, $usuario, $email, $contraseña) {
        $usuarioNuevo = new RegistroModel();
        $usuarioNuevo->nombre = $nombre;
        $usuarioNuevo->usuario = $usuario;
        $usuarioNuevo->email = $email;
        $usuarioNuevo->contraseña = Hash::make($contraseña);
        $usuarioNuevo->tipo_usuario = 2;

        return $usuarioNuevo->save();
    }
}