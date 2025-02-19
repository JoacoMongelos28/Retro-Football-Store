<?php

namespace App\Http\Controllers;

use App\Models\HomeModel;
use App\Models\CarritoProductoModel;
use App\Models\CarritoModel;
use Illuminate\Support\Facades\Log;
use Exception;

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
        try {
            $idUsuario = session('usuarioId');
            $data['camisetasDestacadas'] = $this->homeModel->obtenerCamisetasDestacadas();
            $data['camisetasEnOferta'] = $this->homeModel->obtenerCamisetasEnOfertaDesordenadas();

            if (session('usuarioId') != null) {
                $carrito = $this->carritoModel->obtenerCarritoDelUsuario($idUsuario);
                if ($carrito) {
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
            }

            return view('home', $data);
        } catch (Exception $e) {
            Log::error('Error en el índice: ' . $e->getMessage());
            return redirect('login')->with('error', 'Hubo un problema al cargar la página. Por favor, inténtalo de nuevo.');
        }
    }

    public function obtenerDatosDelCarrito()
    {
        try {
            $idUsuario = session('usuarioId');
            $carrito = $this->carritoModel->obtenerCarritoDelUsuario($idUsuario);

            if ($carrito) {
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
        } catch (Exception $e) {
            Log::error('Error al obtener los datos del carrito: ' . $e->getMessage());
            return [
                'total' => 0,
                'totalCantidad' => 0,
                'carrito' => []
            ];
        }
    }

    public function verCamiseta(HomeModel $camiseta)
    {
        try {
            if (!is_numeric($camiseta->id) || $camiseta->id <= 0) {
                return redirect('home')->with('error', 'El ID de la camiseta no es válido');
            }

            $camisetaObtenida = $this->homeModel->obtenerCamisetaPorId($camiseta->id);

            if (!$camisetaObtenida) {
                return redirect('home')->with('error', 'Camiseta no encontrada');
            }

            $data['usuario'] = session('usuarioId');
            $data['camisetaObtenida'] = $camisetaObtenida;
            $data['tallesDisponibles'] = $this->homeModel->obtenerTallesDisponiblesConStock($data['camisetaObtenida']);
            $data['camisetasEnOferta'] = $this->homeModel->obtenerCamisetasEnOfertaDesordenadas();

            return view('VerCamiseta', $data);
        } catch (Exception $e) {
            Log::error('Error al obtener la camiseta: ' . $e->getMessage());
            return redirect('home')->with('error', 'Hubo un error al cargar la camiseta');
        }
    }

    public function listadoDeCamisetas($filtro = null)
    {
        try {
            session(['filtro' => $filtro]);

            if (session('filtro') != null) {
                $data['filtro'] = session('filtro');
                $data['camisetasFiltradas'] = $this->homeModel->obtenerCamisetasPorFiltro($filtro);
                $data['camisetas'] = $data['camisetasFiltradas']->isEmpty() ? $this->homeModel->obtenerTodasLasCamisetas() : $data['camisetasFiltradas'];
            } else {
                $data['filtro'] = null;	
                $data['camisetas'] = $this->homeModel->obtenerTodasLasCamisetas();
                $data['camisetasFiltradas'] = null;
            }

            return view('listadoCamisetas', $data);
        } catch (Exception $e) {
            Log::error('Error al obtener el listado de camisetas: ' . $e->getMessage());
            return redirect('home')->with('error', 'Hubo un error al cargar las camisetas');
        }
    }
}