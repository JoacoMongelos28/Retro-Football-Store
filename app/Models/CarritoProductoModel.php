<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CarritoProductoModel extends Model
{

    protected $table = 'carrito_camiseta';
    public $timestamps = false;
    protected $fillable = ['carrito_id', 'camiseta_id', 'cantidad'];

    public function agregarCamisetasAlCarrito($idCarrito, $camisetaId, $cantidad)
    {
        $carrito = CarritoProductoModel::where('carrito_id', $idCarrito)
            ->where('camiseta_id', $camisetaId)
            ->first();

        if (!$carrito) {
            $carritoNuevo = CarritoProductoModel::create([
                'carrito_id' => $idCarrito,
                'camiseta_id' => $camisetaId,
                'cantidad' => $cantidad,
            ]);

            return $carritoNuevo;
        } else {
            $carrito->cantidad += $cantidad;
            $carrito->save();
        }

        return $carrito;
    }

    public function obtenerCamisetasDelCarrito($idCarrito)
    {
        $camisetas = CarritoProductoModel::join('camiseta', 'carrito_camiseta.camiseta_id', '=', 'camiseta.id')
            ->select(
                'carrito_camiseta.cantidad',
                'camiseta.precio',
                'camiseta.nombre',
                'camiseta.imagen',
                'camiseta.id',
                DB::raw('carrito_camiseta.cantidad * camiseta.precio as total')
            )->where('carrito_camiseta.carrito_id', $idCarrito)->get();

        foreach ($camisetas as $camiseta) {
            if ($camiseta->imagen) {
                $imagenBase64 = base64_encode($camiseta->imagen);
                $imagenDataUrl = 'data:image/jpeg;base64,' . $imagenBase64;
                $camiseta->imagen = $imagenDataUrl;
            }
        }

        return $camisetas;
    }

    public function joinearCarrito($carritoObtenido)
    {
        if (!$carritoObtenido) return collect();

        return CarritoProductoModel::join('camiseta', 'carrito_camiseta.camiseta_id', '=', 'camiseta.id')
            ->select(
                'camiseta.id',
                'carrito_camiseta.cantidad',
                'camiseta.precio',
                DB::raw('carrito_camiseta.cantidad * camiseta.precio as total')
            )->where('carrito_camiseta.carrito_id', $carritoObtenido->carrito_id)->get();
    }

    public function eliminarCamisetaDelCarrito($idCamiseta, $idCarrito)
    {
        $carritoProducto = CarritoProductoModel::where('carrito_id', $idCarrito)
            ->where('camiseta_id', $idCamiseta)
            ->first();

        if ($carritoProducto) {
            $carritoProducto->delete();
            return true;
        }

        return false;
    }

    public function obtenerCamisetaPorId($idCamiseta) {
        return CarritoProductoModel::where('camiseta_id', $idCamiseta)->first();
    }

    public function actualizarTotalYCantidad($idCarrito) {
        $carritoObtenido = CarritoProductoModel::join('camiseta', 'carrito_camiseta.camiseta_id', '=', 'camiseta.id')
            ->select(
                'carrito_camiseta.cantidad',
                'camiseta.precio',
                DB::raw('carrito_camiseta.cantidad * camiseta.precio as total')
            )->where('carrito_camiseta.carrito_id', $idCarrito)
            ->get();

        return $carritoObtenido;
    }

    public function obtenerProductosDelCarrito($idCarrito) {
        return CarritoProductoModel::where('carrito_id', $idCarrito)->get();
    }
}
