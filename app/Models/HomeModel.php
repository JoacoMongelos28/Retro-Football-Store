<?php

namespace App\Models;

use App\Mail\CompraMail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;

class HomeModel extends Model
{
    protected $table = 'camiseta';
    public $timestamps = false;

    public function obtenerCamisetasDestacadas()
    {
        $camisetasDestacadas = $this->where('estado', 1)->get();

        $camisetasDestacadas = $camisetasDestacadas->take(12);

        return $camisetasDestacadas->map(function ($camiseta) {
            $camiseta->imagen = $this->convertirImagenABase64($camiseta->imagen);

            if ($camiseta->imagen_trasera) {
                $camiseta->imagen_trasera = $this->convertirImagenABase64($camiseta->imagen_trasera);
            }

            return $camiseta;
        });
    }

    public function obtenerCamisetaPorId($id)
    {
        $camiseta = $this->find($id);

        if ($camiseta) {
            $camiseta->imagen = $this->convertirImagenABase64($camiseta->imagen);

            if ($camiseta->imagen_trasera) {
                $camiseta->imagen_trasera = $this->convertirImagenABase64($camiseta->imagen_trasera);
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
        $camisetas = $this->paginate(12);

        $camisetas->getCollection()->transform(function ($camiseta) {
            $camiseta->imagen = $this->convertirImagenABase64($camiseta->imagen);

            if ($camiseta->imagen_trasera) {
                $camiseta->imagen_trasera = $this->convertirImagenABase64($camiseta->imagen_trasera);
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
                $camiseta->imagen = $this->convertirImagenABase64($camiseta->imagen);

                if ($camiseta->imagen_trasera) {
                    $camiseta->imagen_trasera = $this->convertirImagenABase64($camiseta->imagen_trasera);
                }
    
                return $camiseta;
            });
    }

    public function actualizarStock($carrito)
    {
        foreach ($carrito as $camiseta) {
            $camisetaModel = $this->find($camiseta->id);

            $talles = [
                'stock_talle_s' => 'stock_talle_s',
                'stock_talle_m' => 'stock_talle_m',
                'stock_talle_l' => 'stock_talle_l',
                'stock_talle_xl' => 'stock_talle_xl',
                'stock_talle_xxl' => 'stock_talle_xxl',
                'stock_talle_xs' => 'stock_talle_xs',
            ];

            if (isset($talles[$camiseta->talle])) {
                $camisetaModel->{$talles[$camiseta->talle]} -= $camiseta->cantidad;
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

            $talles = [
                'stock_talle_s' => 'S',
                'stock_talle_m' => 'M',
                'stock_talle_l' => 'L',
                'stock_talle_xl' => 'XL',
                'stock_talle_xxl' => 'XXL',
                'stock_talle_xs' => 'XS',
            ];

            if (isset($talles[$producto->talle])) {
                $producto->talle = $talles[$producto->talle];
            }
        }

        Mail::to($email)->send(new CompraMail($productos));
    }

    public function obtenerCamisetasPorFiltro($filtro)
    {
        $camisetas = $this->where('nombre', 'like', '%' . $filtro . '%')->paginate(12);

        $camisetas->getCollection()->transform(function ($camiseta) {
            $camiseta->imagen = $this->convertirImagenABase64($camiseta->imagen);

            if ($camiseta->imagen_trasera) {
                $camiseta->imagen_trasera = $this->convertirImagenABase64($camiseta->imagen_trasera);
            }

            return $camiseta;
        });

        return $camisetas;
    }

    private function convertirImagenABase64($imagen)
    {
        if ($imagen && !preg_match('/^data:image\/(jpeg|png|gif);base64,/', $imagen)) {
            return 'data:image/jpeg;base64,' . base64_encode($imagen);
        }
        return $imagen;
    }
}