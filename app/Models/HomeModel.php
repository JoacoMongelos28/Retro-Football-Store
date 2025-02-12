<?php

namespace App\Models;

use App\Mail\CompraMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;

class HomeModel extends Model
{
    protected $table = 'camiseta';
    public $timestamps = false;

    public function obtenerCamisetasDestacadas()
    {
        $camisetasDestacadas = $this->where('estado', 1)->get();

        $camisetasDestacadas = $camisetasDestacadas->shuffle()->take(12);

        return $camisetasDestacadas->map(function ($camiseta) {
            if ($camiseta->imagen && $camiseta->imagen_trasera) {
                $imagenBase64 = base64_encode($camiseta->imagen);
                $imagenTraseraBase64 = base64_encode($camiseta->imagen_trasera);
                $camiseta->imagen = 'data:image/jpeg;base64,' . $imagenBase64;
                $camiseta->imagen_trasera = 'data:image/jpeg;base64,' . $imagenTraseraBase64;
            } else {
                $imagenBase64 = base64_encode($camiseta->imagen);
                $imagenDataUrl = 'data:image/jpeg;base64,' . $imagenBase64;
                $camiseta->imagen = $imagenDataUrl;
            }
            return $camiseta;
        });
    }

    public function obtenerCamisetaPorId($id)
    {
        $camiseta = $this->find($id);

        if ($camiseta) {
            if ($camiseta->imagen && $camiseta->imagen_trasera) {
                $imagenBase64 = base64_encode($camiseta->imagen);
                $imagenTraseraBase64 = base64_encode($camiseta->imagen_trasera);
                $imagenDataUrl = 'data:image/jpeg;base64,' . $imagenBase64;
                $imagenTraseraDataUrl = 'data:image/jpeg;base64,' . $imagenTraseraBase64;
                $camiseta->imagen = $imagenDataUrl;
                $camiseta->imagen_trasera = $imagenTraseraDataUrl;
            } else {
                $imagenBase64 = base64_encode($camiseta->imagen);
                $imagenDataUrl = 'data:image/jpeg;base64,' . $imagenBase64;
                $camiseta->imagen = $imagenDataUrl;
            }
        }

        return $camiseta;
    }

    public function obtenerTallesDisponiblesConStock($camiseta)
    {
        $tallesDisponibles = [];

        if ($camiseta->stock_talle_s > 0) $tallesDisponibles[] = ['talle' => 'S', 'stock' => $camiseta->stock_talle_s];
        if ($camiseta->stock_talle_m > 0) $tallesDisponibles[] = ['talle' => 'M', 'stock' => $camiseta->stock_talle_m];
        if ($camiseta->stock_talle_l > 0) $tallesDisponibles[] = ['talle' => 'L', 'stock' => $camiseta->stock_talle_l];
        if ($camiseta->stock_talle_xl > 0) $tallesDisponibles[] = ['talle' => 'XL', 'stock' => $camiseta->stock_talle_xl];
        if ($camiseta->stock_talle_xxl > 0) $tallesDisponibles[] = ['talle' => 'XXL', 'stock' => $camiseta->stock_talle_xxl];
        if ($camiseta->stock_talle_xs > 0) $tallesDisponibles[] = ['talle' => 'XS', 'stock' => $camiseta->stock_talle_xs];

        return $tallesDisponibles;
    }

    public function obtenerTodasLasCamisetas()
    {
        $camisetas = $this->paginate(1);

        $camisetas->getCollection()->transform(function ($camiseta) {
            if ($camiseta->imagen) {
                $camiseta->imagen = 'data:image/jpeg;base64,' . base64_encode($camiseta->imagen);
            }
            return $camiseta;
        });

        return $camisetas;
    }

    public function obtenerCamisetasEnOfertaDesordenadas()
    {
        return $this->where('estado', 2)
            ->inRandomOrder()
            ->get()
            ->map(function ($camiseta) {
                if ($camiseta->imagen) {
                    $imagenBase64 = base64_encode($camiseta->imagen);
                    $camiseta->imagen = 'data:image/jpeg;base64,' . $imagenBase64;
                }
                return $camiseta;
            });
    }

    public function actualizarStock($carrito)
    {
        foreach ($carrito as $camiseta) {
            $camisetaModel = $this->find($camiseta->id);

            if ($camiseta->talle === 'stock_talle_s') {
                $camisetaModel->stock_talle_s -= $camiseta->cantidad;
                $camisetaModel->save();
            } else if ($camiseta->talle === 'stock_talle_m') {
                $camisetaModel->stock_talle_m -= $camiseta->cantidad;
                $camisetaModel->save();
            } else if ($camiseta->talle === 'stock_talle_l') {
                $camisetaModel->stock_talle_l -= $camiseta->cantidad;
                $camisetaModel->save();
            } else if ($camiseta->talle === 'stock_talle_xl') {
                $camisetaModel->stock_talle_xl -= $camiseta->cantidad;
                $camisetaModel->save();
            } else if ($camiseta->talle === 'stock_talle_xxl') {
                $camisetaModel->stock_talle_xxl -= $camiseta->cantidad;
                $camisetaModel->save();
            } else if ($camiseta->talle === 'stock_talle_xs') {
                $camisetaModel->stock_talle_xs -= $camiseta->cantidad;
                $camisetaModel->save();
            } else {
                return false;
            }
        }
    }

    public function enviarMail($productos, $email)
    {
        foreach ($productos as $producto) {
            $producto->imagen = 'data:image/jpeg;base64,' . base64_encode($producto->imagen);

            if ($producto->talle === 'stock_talle_s') {
                $producto->talle = 'S';
            } else if ($producto->talle === 'stock_talle_m') {
                $producto->talle = 'M';
            } else if ($producto->talle === 'stock_talle_l') {
                $producto->talle = 'L';
            } else if ($producto->talle === 'stock_talle_xl') {
                $producto->talle = 'XL';
            } else if ($producto->talle === 'stock_talle_xxl') {
                $producto->talle = 'XXL';
            } else if ($producto->talle === 'stock_talle_xs') {
                $producto->talle = 'XS';
            }
        }

        Mail::to($email)->send(new CompraMail($productos));
    }
}