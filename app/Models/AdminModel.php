<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminModel extends Model
{
    protected $table = 'camiseta';
    public $timestamps = false;

    public function guardarCamiseta($nombre, $descripcion, $precio, $estado, $imagen, $cantidadXS, $cantidadS, $cantidadM, $cantidadL, $cantidadXL, $cantidadXXL) {
        $camiseta = new AdminModel();

        $camiseta->nombre = $nombre;
        $camiseta->descripcion = $descripcion;
        $camiseta->precio = $precio;
        $camiseta->estado = $estado;
        $camiseta->imagen = $imagen;
        $camiseta->stock_talle_xs = $cantidadXS;
        $camiseta->stock_talle_s = $cantidadS;
        $camiseta->stock_talle_m = $cantidadM;
        $camiseta->stock_talle_l = $cantidadL;
        $camiseta->stock_talle_xl = $cantidadXL;
        $camiseta->stock_talle_xxl = $cantidadXXL;

        return $camiseta->save();
    }

    public function obtenerTodasLasCamisetas() {
        $camisetas = AdminModel::all(); 
    
        foreach ($camisetas as $camiseta) {
            if ($camiseta->imagen) {
                $imagenBase64 = base64_encode($camiseta->imagen);
                $imagenDataUrl = 'data:image/jpeg;base64,' . $imagenBase64;
                $camiseta->imagen = $imagenDataUrl;
            }
        }
    
        return $camisetas;
    }

    public function obtenerCamisetaPorId($id) {
        return AdminModel::find($id);
    }

    public function actualizarCamiseta($id, $nombre, $descripcion, $precio, $estado, $imagen, $cantidadXS, $cantidadS, $cantidadM, $cantidadL, $cantidadXL, $cantidadXXL) {
        $camiseta = AdminModel::find($id);

        $camiseta->nombre = $nombre;
        $camiseta->descripcion = $descripcion;
        $camiseta->precio = $precio;
        $camiseta->estado = $estado;
        $camiseta->imagen = $imagen ?? $camiseta->imagen;
        $camiseta->stock_talle_xs += $cantidadXS;
        $camiseta->stock_talle_s += $cantidadS;
        $camiseta->stock_talle_m += $cantidadM;
        $camiseta->stock_talle_l += $cantidadL;
        $camiseta->stock_talle_xl += $cantidadXL;
        $camiseta->stock_talle_xxl += $cantidadXXL;

        return $camiseta->save();
    }

    public function eliminarCamiseta($id) {
        $camiseta = AdminModel::find($id);
        return $camiseta->delete();
    }
}