<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarritoModel extends Model
{
    protected $table = 'carrito';
    public $timestamps = false;
    protected $fillable = ['usuario_id'];

    public function crearCarrito($idUsuario)
    {
        $carrito = CarritoModel::where('usuario_id', $idUsuario)->first();

        if (!$carrito) {
            $carrito = CarritoModel::create([
                'usuario_id' => $idUsuario
            ]);
        }

        return $carrito;
    }

    public function obtenerCarrito($idUsuario)
    {
        $carrito = CarritoModel::where('usuario_id', $idUsuario)->first();

        if (!$carrito) {
            $carrito = $this->crearCarrito($idUsuario);
        }

        return $carrito;
    }

    public function obtenerCarritoJoineado($idUsuario, $idCamiseta)
    {
        $carrito = $this->obtenerCarrito($idUsuario);

        $carritoConProductos = CarritoModel::join('carrito_camiseta', 'carrito.id', '=', 'carrito_camiseta.carrito_id')
            ->select('carrito_camiseta.camiseta_id',
            'carrito.id')
            ->where('carrito.id', $carrito->id)
            ->where('carrito_camiseta.camiseta_id', $idCamiseta)
            ->first();

        return $carritoConProductos;
    }

    public function joinearCarritoPorSuId($idCarrito)
    {
        $carritoConProductos = CarritoModel::join('carrito_camiseta', 'carrito.id', '=', 'carrito_camiseta.carrito_id')
            ->select('carrito_camiseta.camiseta_id',
            'carrito_camiseta.cantidad',
            'carrito_camiseta.carrito_id')
            ->where('carrito_camiseta.carrito_id', $idCarrito)
            ->get();

        return $carritoConProductos;
    }

    public function eliminarCarritoPorIdUsuario($idUsuario)
    {
        $carrito = CarritoModel::where('usuario_id', $idUsuario)->first();

        if ($carrito) {
            $carrito->delete();
        }
    }
}