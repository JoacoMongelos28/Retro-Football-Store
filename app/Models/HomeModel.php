<?php

namespace App\Models;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class HomeModel extends Model
{
    protected $table = 'camiseta';
    public $timestamps = false;

    public function obtenerCamisetasDestacadas()
    {
        $camisetasDestacadas = $this->where('estado', 1)->get();

        foreach ($camisetasDestacadas as $camiseta) {
            if ($camiseta->imagen) {
                $imagenBase64 = base64_encode($camiseta->imagen);
                $imagenDataUrl = 'data:image/jpeg;base64,' . $imagenBase64;
                $camiseta->imagen = $imagenDataUrl;
            }
        }

        return $camisetasDestacadas;
    }

    public function obtenerCamisetasEnOferta()
    {
        $camisetasEnOferta = $this->where('estado', 2)->get();

        foreach ($camisetasEnOferta as $camiseta) {
            if ($camiseta->imagen) {
                $imagenBase64 = base64_encode($camiseta->imagen);
                $imagenDataUrl = 'data:image/jpeg;base64,' . $imagenBase64;
                $camiseta->imagen = $imagenDataUrl;
            }
        }

        return $camisetasEnOferta;
    }

    public function obtenerCamisetaPorId($id)
    {
        $camiseta = $this->find($id);

        if ($camiseta) {
            if ($camiseta->imagen) {
                $imagenBase64 = base64_encode($camiseta->imagen);
                $imagenDataUrl = 'data:image/jpeg;base64,' . $imagenBase64;
                $camiseta->imagen = $imagenDataUrl;
            }
        }

        return $camiseta;
    }

    public function obtenerTodasLasCamisetas()
    {
        $camisetas = $this->all();

        foreach ($camisetas as $camiseta) {
            if ($camiseta->imagen) {
                $imagenBase64 = base64_encode($camiseta->imagen);
                $imagenDataUrl = 'data:image/jpeg;base64,' . $imagenBase64;
                $camiseta->imagen = $imagenDataUrl;
            }
        }

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
                return true;
            } else if ($camiseta->talle === 'stock_talle_m') {
                $camisetaModel->stock_talle_m -= $camiseta->cantidad;
                $camisetaModel->save();
                return true;
            } else if ($camiseta->talle === 'stock_talle_l') {
                $camisetaModel->stock_talle_l -= $camiseta->cantidad;
                $camisetaModel->save();
                return true;
            } else if ($camiseta->talle === 'stock_talle_xl') {
                $camisetaModel->stock_talle_xl -= $camiseta->cantidad;
                $camisetaModel->save();
                return true;
            } else if ($camiseta->talle === 'stock_talle_xxl') {
                $camisetaModel->stock_talle_xxl -= $camiseta->cantidad;
                $camisetaModel->save();
                return true;
            } else if ($camiseta->talle === 'stock_talle_xs') {
                $camisetaModel->stock_talle_xxxl -= $camiseta->cantidad;
                $camisetaModel->save();
                return true;
            } else {
                return false;
            }
            return false;
        }
    }
}
