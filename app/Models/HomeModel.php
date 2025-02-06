<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomeModel extends Model
{
    protected $table = 'camiseta';

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
}
