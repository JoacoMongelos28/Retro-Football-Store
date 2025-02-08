<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CarritoModel;
use App\Models\CarritoProductoModel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\MercadoPagoModel;
use App\Models\HomeModel;

class CarritoController extends Controller
{
    private $carritoModel;
    private $carritoProductoModel;
    private $mercadoPagoModel;
    private $homeModel;

    public function __construct(CarritoModel $carritoModel, CarritoProductoModel $carritoProductoModel, MercadoPagoModel $mercadoPagoModel, HomeModel $homeModel)
    {
        $this->carritoModel = $carritoModel;
        $this->carritoProductoModel = $carritoProductoModel;
        $this->mercadoPagoModel = $mercadoPagoModel;
        $this->homeModel = $homeModel;
    }

    public function agregarAlCarrito(Request $request)
    {
        $idUsuario = session('usuarioId');

        if (isset($idUsuario) && is_int($idUsuario)) {

            $carritoObtenido = $this->carritoModel->crearCarrito($idUsuario);

            $talle = $request->input('talle');

            Log::info('Talle: ' . $talle);

            $cantidad = $request->input('cantidad');

            $validator = Validator::make($request->all(), [
                'talle' => 'required',
                'cantidad' => 'required|numeric|min:1',
            ], [
                'talle.required' => 'Debe seleccionar un talle antes de agregar al carrito.',
                'cantidad.required' => 'Debe ingresar una cantidad.',
                'cantidad.numeric' => 'La cantidad debe ser un número.',
                'cantidad.min' => 'Debe agregar al menos una unidad.',
            ]);
        
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()->first()], 400);
            }

            $camisetaId = $request->input('id');

            $carritoObtenido = $this->carritoProductoModel->agregarCamisetasAlCarrito($carritoObtenido->id, $camisetaId, $cantidad, $talle);

            $carritoJoineado = $this->carritoProductoModel->joinearCarrito($carritoObtenido);

            $total = 0;
            $cantidadTotal = 0;

            foreach ($carritoJoineado as $camiseta) {
                $total += $camiseta->total;
                $cantidadTotal += $camiseta->cantidad;
            }

            return response()->json([
                'mensaje' => 'Producto agregado al carrito',
                'carrito' => [
                    'cantidad' => $cantidadTotal,
                    'total' => $total
                ],
            ]);
        }

        return response('No se pudo agregar el producto al carrito', 401);
    }

    public function mostrarCarrito()
    {
        if (!session('usuarioId')) {
            return redirect('login');
        }
        
        $idUsuario = session('usuarioId');
        $carritoObtenido = $this->carritoModel->obtenerCarrito($idUsuario);
        $carritoJoineado = $this->carritoProductoModel->obtenerCamisetasDelCarrito($carritoObtenido);
        $data['carrito'] = $carritoJoineado;
        return view('Carrito', $data);
    }

    public function eliminarCamisetaDelCarrito(Request $request)
    {
        $idUsuario = session('usuarioId');
        $idCamiseta = $request->input('id');

        $carrito = $this->carritoModel->obtenerCarritoJoineado($idUsuario, $idCamiseta);

        $resultado = $this->carritoProductoModel->eliminarCamisetaDelCarrito($carrito->camiseta_id, $carrito->id);

        if ($resultado) {
            $carritoRestante = $this->carritoProductoModel->obtenerProductosDelCarrito($carrito->id);

            if ($carritoRestante->isEmpty()) {
                return response()->json([
                    'mensaje' => 'Producto eliminado del carrito',
                    'cantidadTotal' => 0,
                    'total' => 0
                ]);
            } else {
                $nuevosValores = $this->carritoProductoModel->actualizarTotalYCantidad($carrito->id);

                $totalCantidad = 0;
                $totalHeader = 0;

                foreach ($nuevosValores as $camiseta) {
                    $totalCantidad += $camiseta->cantidad;
                    $totalHeader += $camiseta->total;
                }

                return response()->json([
                    'mensaje' => 'Producto eliminado del carrito',
                    'cantidadTotal' => $totalCantidad,
                    'total' => $totalHeader
                ]);
            }
        }

        return response()->json(['mensaje' => 'No se pudo eliminar el producto del carrito']);
    }

    public function actualizarCantidad(Request $request)
    {
        $idCamiseta = $request->input('id');
        $accion = $request->input('accion');

        $carritoProducto = $this->carritoProductoModel->obtenerCamisetaPorId($idCamiseta);

        if (!$carritoProducto) {
            return response()->json(['success' => false, 'message' => 'Producto no encontrado']);
        }

        $carritoProducto->cantidad += $accion;
        $carritoProducto->cantidad = max(1, $carritoProducto->cantidad);
        $carritoProducto->save();

        $carritoJoineado = $this->carritoProductoModel->joinearCarrito($carritoProducto);

        $totalCantidad = $carritoJoineado->where('id', $idCamiseta)->sum('cantidad');
        $total = $carritoJoineado->where('id', $idCamiseta)->sum('total');

        $totalCantidadHeader = 0;
        $totalHeader = 0;

        foreach ($carritoJoineado as $producto) {
            $totalCantidadHeader += $producto->cantidad;
            $totalHeader += $producto->total;
        }

        return response()->json([
            'success' => true,
            'nuevaCantidad' => $totalCantidad,
            'nuevoTotal' => number_format($total, 2),
            'totalCantidadHeader' => $totalCantidadHeader,
            'totalHeader' => number_format($totalHeader, 2)
        ]);

        return response()->json(['success' => false]);
    }

    public function procesarPago()
    {
        $usuarioId = session('usuarioId');

        if (empty($usuarioId) || !is_numeric($usuarioId) || $usuarioId <= 0) {
            return redirect('login');
        }

        $carrito = $this->carritoModel->obtenerCarrito($usuarioId);
        $carritoJoineado = $this->carritoModel->joinearCarritoPorSuId($carrito->id);
        $carritoFinal = $this->carritoProductoModel->joinearCarrito($carritoJoineado->first());

        $total = 0;

        foreach ($carritoFinal as $camiseta) {
            $total += $camiseta->total;
        }

        $url = $this->mercadoPagoModel->pagarCamiseta($total, $usuarioId);
        return redirect($url);
    }

    public function pagoExitoso()
    {
        $usuarioId = session('usuarioId');
        $carrito = $this->carritoModel->obtenerCarrito($usuarioId);
        $carritoJoineado = $this->carritoModel->joinearCarritoPorSuId($carrito->id);
        $carritoFinal = $this->carritoProductoModel->obtenerCamisetasAEliminar($carritoJoineado->first());
        
        $this->homeModel->actualizarStock($carritoFinal);
        $this->carritoModel->eliminarCarritoPorIdUsuario($usuarioId);

        return redirect('carrito')->with('exitoso', 'Pago exitoso');
    }
}
