<?php

namespace App\Http\Controllers;

use App\Models\HomeModel;
use App\Models\CarritoProductoModel;
use App\Models\CarritoModel;

class HomeController extends Controller
{

    private $homeModel;
    private $carritoModel;
    private $carritoCamisetaModel;

    public function __construct()
    {
        $this->homeModel = new HomeModel();
        $this->carritoCamisetaModel = new CarritoProductoModel();
        $this->carritoModel = new CarritoModel();
    }

    public function index()
    {
        $idUsuario = session('usuarioId');
        $data['camisetasDestacadas'] = $this->homeModel->obtenerCamisetasDestacadas();
        $data['camisetasEnOferta'] = $this->homeModel->obtenerCamisetasEnOfertaDesordenadas();

        if (session('usuarioId') != null) {
            $carrito = $this->carritoModel->obtenerCarrito($idUsuario);
            $carritoConProductos = $this->carritoCamisetaModel->obtenerCamisetasDelCarrito($carrito->id);

            $totalCantidad = 0;
            $total = 0;

            foreach ($carritoConProductos as $producto) {
                $totalCantidad += $producto->cantidad;
                $total += $producto->total;
            }

            $data['total'] = $total;
            $data['totalCantidad'] = $totalCantidad;
            $data['carrito'] = $carritoConProductos;
        }

        return view('home', $data);
    }

    public function obtenerDatosDelCarrito()
    {
        $idUsuario = session('usuarioId');

        if (session('usuarioId') != null) {

            $carrito = $this->carritoModel->obtenerCarrito($idUsuario);

            $carritoConProductos = $this->carritoCamisetaModel->obtenerCamisetasDelCarrito($carrito->id);

            $totalCantidad = 0;
            $total = 0;

            foreach ($carritoConProductos as $producto) {
                $totalCantidad += $producto->cantidad;
                $total += $producto->total;
            }

            return [
                'total' => $total,
                'totalCantidad' => $totalCantidad,
                'carrito' => $carritoConProductos
            ];
        }

        return [
            'total' => 0,
            'totalCantidad' => 0,
            'carrito' => []
        ];
    }

    public function verCamiseta(HomeModel $camiseta)
    {
        if (!is_numeric($camiseta->id) || $camiseta->id <= 0) {
            return redirect('home')->with('error', 'El ID de la camiseta no es válido');
        }

        $data['camisetaObtenida'] = $this->homeModel->obtenerCamisetaPorId($camiseta->id);
        $data['tallesDisponibles'] = $this->homeModel->obtenerTallesDisponiblesConStock($data['camisetaObtenida']);
        $data['camisetasEnOferta'] = $this->homeModel->obtenerCamisetasEnOfertaDesordenadas();
        return view('VerCamiseta', $data);
    }

    public function listadoDeCamisetas()
    {
        $data['camisetas'] = $this->homeModel->obtenerTodasLasCamisetas();
        return view('ListadoCamisetas', $data);
    }
}