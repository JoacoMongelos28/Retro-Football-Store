<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class AdminModel extends Model
{
    protected $table = 'camiseta';
    public $timestamps = false;

    public function guardarCamiseta($nombre, $descripcion, $precio, $estado, $imagen, $imagenTrasera, $cantidadXS, $cantidadS, $cantidadM, $cantidadL, $cantidadXL, $cantidadXXL)
    {
        $camiseta = new AdminModel();

        $camiseta->nombre = $nombre;
        $camiseta->descripcion = $descripcion;
        $camiseta->precio = $precio;
        $camiseta->estado = $estado;
        $camiseta->imagen = $imagen;
        $camiseta->imagen_trasera = $imagenTrasera;
        $camiseta->stock_talle_xs = $cantidadXS;
        $camiseta->stock_talle_s = $cantidadS;
        $camiseta->stock_talle_m = $cantidadM;
        $camiseta->stock_talle_l = $cantidadL;
        $camiseta->stock_talle_xl = $cantidadXL;
        $camiseta->stock_talle_xxl = $cantidadXXL;

        static::creating(function ($camiseta) {
            $slug = Str::slug($camiseta->nombre);
            $count = AdminModel::where('slug', 'LIKE', "{$slug}%")->count();
            $camiseta->slug = $count ? "{$slug}-{$count}" : $slug;
        });

        return $camiseta->save();
    }

    public function obtenerTodasLasCamisetas()
    {
        $camisetas = AdminModel::all();

        foreach ($camisetas as $camiseta) {
            if ($camiseta->imagen) {
                $imagenBase64 = base64_encode($camiseta->imagen);
                $imagenDataUrl = 'data:image/jpeg;base64,' . $imagenBase64;
                $camiseta->imagen = $imagenDataUrl;
            }

            if ($camiseta->imagen_trasera) {
                $imagenTraseraBase64 = base64_encode($camiseta->imagen_trasera);
                $imagenTraseraDataUrl = 'data:image/jpeg;base64,' . $imagenTraseraBase64;
                $camiseta->imagen_trasera = $imagenTraseraDataUrl;
            }
        }

        return $camisetas;
    }

    public function obtenerCamisetaPorId($id)
    {
        return AdminModel::find($id);
    }

    public function obtenerCamisetaConImagenPorId($id)
    {
        $camiseta = AdminModel::find($id);

        if ($camiseta->imagen) {
            $camiseta->imagen = $this->convertirImagenABase64($camiseta->imagen);
        }

        if ($camiseta->imagen_trasera) {
            $camiseta->imagen_trasera = $this->convertirImagenABase64($camiseta->imagen_trasera);
        }

        return $camiseta;
    }

    public function actualizarCamiseta($id, $nombre, $descripcion, $precio, $estado, $imagen, $imagenTrasera, $cantidadXS, $cantidadS, $cantidadM, $cantidadL, $cantidadXL, $cantidadXXL)
    {
        $camiseta = AdminModel::find($id);

        $camiseta->nombre = $nombre;
        $camiseta->descripcion = $descripcion;
        $camiseta->precio = $precio;
        $camiseta->estado = $estado;
        $camiseta->imagen = $imagen ?? $camiseta->imagen;
        $camiseta->imagen_trasera = $imagenTrasera ?? $camiseta->imagen_trasera;
        $camiseta->stock_talle_xs += $cantidadXS;
        $camiseta->stock_talle_s += $cantidadS;
        $camiseta->stock_talle_m += $cantidadM;
        $camiseta->stock_talle_l += $cantidadL;
        $camiseta->stock_talle_xl += $cantidadXL;
        $camiseta->stock_talle_xxl += $cantidadXXL;

        static::creating(function ($camiseta) {
            $slug = Str::slug($camiseta->nombre);
            $count = AdminModel::where('slug', 'LIKE', "{$slug}%")->count();
            $camiseta->slug = $count ? "{$slug}-{$count}" : $slug;
        });

        return $camiseta->save();
    }

    public function eliminarCamiseta($id)
    {
        $camiseta = AdminModel::findOrFail($id);
        return $camiseta->delete();
    }

    public function filtrarCamisetas($filtro)
    {
        $query = AdminModel::query();

        $filtrosValidos = [
            'todos' => fn($query) => $query,
            'precio-menor-mayor' => fn($query) => $query->orderBy('precio', 'asc'),
            'precio-mayor-menor' => fn($query) => $query->orderBy('precio', 'desc'),
            'destacados' => fn($query) => $query->where('estado', 1),
            'ofertas' => fn($query) => $query->where('estado', 2),
        ];

        $camisetas = $filtrosValidos[$filtro] ?? fn($query) => $query;
        $camisetas = $camisetas($query)->get();

        $camisetas->transform(fn($camiseta) => $this->procesarImagenes($camiseta));

        return $camisetas;
    }

    private function procesarImagenes($camiseta)
    {
        if ($camiseta->imagen) {
            $camiseta->imagen = $this->convertirImagenABase64($camiseta->imagen);
        }
        if ($camiseta->imagen_trasera) {
            $camiseta->imagen_trasera = $this->convertirImagenABase64($camiseta->imagen_trasera);
        }
        return $camiseta;
    }

    private function convertirImagenABase64($imagen)
    {
        if ($imagen && !preg_match('/^data:image\/(jpeg|png|gif);base64,/', $imagen)) {
            return 'data:image/jpeg;base64,' . base64_encode($imagen);
        }
        return $imagen;
    }
}