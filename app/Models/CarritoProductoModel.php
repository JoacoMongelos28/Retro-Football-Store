<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CarritoProductoModel extends Model
{

    protected $table = 'carrito_camiseta';
    public $timestamps = false;
    protected $fillable = ['carrito_id', 'camiseta_id', 'cantidad', 'talle'];

    public function agregarCamisetasAlCarrito($idCarrito, $camisetaId, $cantidad, $talle)
    {
        $carrito = CarritoProductoModel::where('carrito_id', $idCarrito)
            ->where('camiseta_id', $camisetaId)
            ->first();

        if (!$carrito) {
            $carritoNuevo = CarritoProductoModel::create([
                'carrito_id' => $idCarrito,
                'camiseta_id' => $camisetaId,
                'cantidad' => $cantidad,
                'talle' => $talle
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
                'camiseta.slug',
                'carrito_camiseta.talle',
                DB::raw('carrito_camiseta.cantidad * camiseta.precio as total'),
                DB::raw("CASE 
                WHEN carrito_camiseta.talle = 'stock_talle_s' THEN camiseta.stock_talle_s
                WHEN carrito_camiseta.talle = 'stock_talle_m' THEN camiseta.stock_talle_m
                WHEN carrito_camiseta.talle = 'stock_talle_l' THEN camiseta.stock_talle_l
                WHEN carrito_camiseta.talle = 'stock_talle_xl' THEN camiseta.stock_talle_xl
                WHEN carrito_camiseta.talle = 'stock_talle_xxl' THEN camiseta.stock_talle_xxl
                WHEN carrito_camiseta.talle = 'stock_talle_xs' THEN camiseta.stock_talle_xs
                ELSE 0 END AS stock_disponible")
            )->where('carrito_camiseta.carrito_id', $idCarrito)->get();

        foreach ($camisetas as $camiseta) {
            $camiseta->talle = str_replace('stock_talle_', '', $camiseta->talle);

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

    public function obtenerCamisetasAEliminar($carritoObtenido)
    {
        if (!$carritoObtenido) return collect();
        return CarritoProductoModel::join('camiseta', 'carrito_camiseta.camiseta_id', '=', 'camiseta.id')
            ->select(
                'camiseta.id',
                'carrito_camiseta.cantidad',
                'camiseta.precio',
                'carrito_camiseta.talle',
                'camiseta.nombre',
                'camiseta.slug',
                'camiseta.imagen',
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

    public function obtenerCamisetaPorId($idCamiseta)
    {
        return CarritoProductoModel::where('camiseta_id', $idCamiseta)->first();
    }

    public function actualizarTotalYCantidad($idCarrito)
    {
        $carritoObtenido = CarritoProductoModel::join('camiseta', 'carrito_camiseta.camiseta_id', '=', 'camiseta.id')
            ->select(
                'carrito_camiseta.cantidad',
                'camiseta.precio',
                DB::raw('carrito_camiseta.cantidad * camiseta.precio as total')
            )->where('carrito_camiseta.carrito_id', $idCarrito)
            ->get();

        return $carritoObtenido;
    }

    public function obtenerProductosDelCarrito($idCarrito)
    {
        return CarritoProductoModel::where('carrito_id', $idCarrito)->get();
    }
}
