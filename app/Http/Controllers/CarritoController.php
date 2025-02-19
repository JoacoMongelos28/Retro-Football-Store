<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CarritoModel;
use App\Models\CarritoProductoModel;
use Illuminate\Support\Facades\Validator;
use App\Models\MercadoPagoModel;
use App\Models\HomeModel;
use App\Models\LoginModel;
use Illuminate\Support\Facades\Log;
use Exception;

class CarritoController extends Controller
{
    private $carritoModel;
    private $carritoProductoModel;
    private $mercadoPagoModel;
    private $homeModel;
    private $loginModel;

    public function __construct(CarritoModel $carritoModel, CarritoProductoModel $carritoProductoModel, MercadoPagoModel $mercadoPagoModel, HomeModel $homeModel, LoginModel $loginModel)
    {
        $this->carritoModel = $carritoModel;
        $this->carritoProductoModel = $carritoProductoModel;
        $this->mercadoPagoModel = $mercadoPagoModel;
        $this->homeModel = $homeModel;
        $this->loginModel = $loginModel;
    }

    public function agregarAlCarrito(Request $request)
    {
        try {
            $idUsuario = session('usuarioId');

            if (empty($idUsuario) || !is_int($idUsuario) || $idUsuario <= 0) {
                return response()->json([
                    'error' => 'Debe iniciar sesión para agregar productos al carrito',
                    'redirect' => $request->input('url')
                ], 401);
            }

            $camiseta = $this->homeModel->obtenerCamisetaPorId($request->input('id'));
            $talle = $request->input('talle');
            $cantidad = $request->input('cantidad');
            $stockDisponible = $camiseta ? $camiseta->{$talle} : 0;

            $validator = Validator::make($request->all(), [
                'talle' => 'required',
                'cantidad' => 'required|numeric|min:1',
            ], [
                'talle.required' => 'Debe seleccionar un talle',
                'cantidad.required' => 'Debe ingresar una cantidad.',
                'cantidad.numeric' => 'La cantidad debe ser un número.',
                'cantidad.min' => 'Debe agregar al menos una unidad.',
            ]);

            $validator->after(function ($validator) use ($cantidad, $stockDisponible) {
                if ($cantidad > $stockDisponible) {
                    $validator->errors()->add('cantidad', 'Solo hay ' . $stockDisponible . ' camisetas disponibles en el talle seleccionado.');
                }
            });

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()->first()], 400);
            }

            $carritoObtenido = $this->carritoModel->crearCarrito($idUsuario);

            $productoEnCarrito = $this->carritoProductoModel->obtenerProductoEnCarrito($carritoObtenido->id, $camiseta->id);

            if ($productoEnCarrito) {
                $nuevaCantidad = $productoEnCarrito->cantidad + $cantidad;
                if ($nuevaCantidad > $stockDisponible) {
                    return response()->json([
                        'error' => 'No puedes agregar más unidades de las disponibles en stock.'
                    ], 400);
                }

                $productoEnCarrito->cantidad = $nuevaCantidad;
                $carritoObtenido = $this->carritoProductoModel->agregarCamisetasExisatenteAlCarrito($camiseta->id, $carritoObtenido->id, $nuevaCantidad);
            } else {
                $carritoObtenido = $this->carritoProductoModel->agregarCamisetasAlCarrito(
                    $carritoObtenido->id,
                    $camiseta->id,
                    $cantidad,
                    $talle
                );
            }

            $carritoFinal = $this->carritoProductoModel->obtenerPrecioTotalDeLosProductosEnElCarrito($carritoObtenido);

            $total = 0;
            $cantidadTotal = 0;

            foreach ($carritoFinal as $camiseta) {
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
        } catch (Exception $e) {
            Log::error('Error al agregar al carrito: ' . $e->getMessage());
            return response()->json(['error' => 'Ocurrió un problema al procesar la solicitud.'], 500);
        }
    }

    public function mostrarCarrito()
    {
        try {
            if (!session('usuarioId')) {
                return redirect('login');
            }

            $idUsuario = session('usuarioId');
            $carritoObtenido = $this->carritoModel->obtenerCarritoDelUsuario($idUsuario);

            if (!$carritoObtenido) {
                return redirect('home')->with('error', 'No se pudo obtener el carrito.');
            }

            $carritoJoineado = $this->carritoProductoModel->obtenerCamisetasDelCarrito($carritoObtenido);

            if (!$carritoJoineado) {
                return redirect('home')->with('error', 'No se encontraron productos en el carrito.');
            }

            $data['carrito'] = $carritoJoineado;

            return view('Carrito', $data);
        } catch (Exception $e) {
            Log::error("Error al mostrar el carrito: " . $e->getMessage());
            return redirect('carrito')->with('error', 'Hubo un problema al cargar el carrito.');
        }
    }

    public function eliminarCamisetaDelCarrito(Request $request)
    {
        try {
            if (!session('usuarioId')) {
                return redirect('login');
            }

            $idUsuario = session('usuarioId');
            $idCamiseta = $request->input('id');

            $carrito = $this->carritoModel->obtenerCamisetaDelCarritoPorSuId($idUsuario, $idCamiseta);

            if (!$carrito) {
                return response()->json(['error' => 'Producto no encontrado en el carrito'], 404);
            }

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

            return response()->json(['mensaje' => 'No se pudo eliminar el producto del carrito'], 500);
        } catch (Exception $e) {
            Log::error('Error al mostrar el carrito: ' . $e->getMessage());
            return response()->json([
                'error' => 'Hubo un problema al procesar la solicitud.',
                'detalle' => $e->getMessage(),
            ], 500);
        }
    }

    public function actualizarCantidad(Request $request)
    {
        try {
            if (!session('usuarioId')) {
                return redirect('login');
            }

            $idCamiseta = $request->input('id');
            $accion = $request->input('accion');

            $carritoConCamiseta = $this->carritoProductoModel->obtenerCamisetaPorId($idCamiseta);

            if (!$carritoConCamiseta) {
                return response()->json(['success' => false, 'message' => 'Producto no encontrado']);
            }

            $stockDisponible = $request->input('stock');
            $nuevaCantidad = $carritoConCamiseta->cantidad + $accion;

            if ($nuevaCantidad < 1) {
                $nuevaCantidad = 1;
            } else if ($nuevaCantidad > $stockDisponible) {
                return response()->json([
                    'success' => false,
                    'message' => 'No puedes agregar más unidades de las disponibles en stock.'
                ]);
            }

            $carritoConCamiseta->cantidad = max(1, $carritoConCamiseta->cantidad);
            $carritoConCamiseta->cantidad = $nuevaCantidad;
            $carritoConCamiseta->save();

            $carritoJoineado = $this->carritoProductoModel->obtenerPrecioTotalDeLosProductosEnElCarrito($carritoConCamiseta);

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
        } catch (\Exception $e) {
            // Manejo del error
            return response()->json([
                'success' => false,
                'message' => 'Hubo un error al actualizar la cantidad del producto.',
                'error' => $e->getMessage()
            ]);
        }
    }


    public function procesarPago()
    {
        try {
            if (!session('usuarioId')) {
                return redirect('login');
            }

            $usuarioId = session('usuarioId');
            $usuario = $this->loginModel->obtenerUsuarioPorId($usuarioId);

            if (!$usuario) {
                return redirect('login')->with('error', 'Usuario no encontrado.');
            }

            $carrito = $this->carritoModel->obtenerCarritoDelUsuario($usuarioId);

            if (!$carrito) {
                return redirect('carrito')->with('error', 'Carrito no encontrado.');
            }

            $carritoConProductos = $this->carritoModel->obtenerProductosDelCarrito($carrito->id);

            if ($carritoConProductos->isEmpty()) {
                return redirect('carrito')->with('error', 'No hay productos en el carrito');
            }

            $carritoFinal = $this->carritoProductoModel->obtenerPrecioTotalDeLosProductosEnElCarrito($carritoConProductos->first());

            $total = 0;

            foreach ($carritoFinal as $camiseta) {
                $total += $camiseta->total;
            }

            $url = $this->mercadoPagoModel->pagarCamiseta($total, $usuarioId);
            return redirect($url);
        } catch (Exception $e) {
            Log::error('Error al procesar el pago: ' . $e->getMessage());
            return redirect('carrito')->with('error', 'Hubo un problema al procesar el pago: ' . $e->getMessage());
        }
    }

    public function pagoExitoso()
    {
        try {
            if (!session('usuarioId')) {
                return redirect('login');
            }

            $usuarioId = session('usuarioId');
            $usuario = $this->loginModel->obtenerUsuarioPorId($usuarioId);

            if (!$usuario) {
                return redirect('login')->with('error', 'Usuario no encontrado.');
            }

            $carrito = $this->carritoModel->obtenerCarritoDelUsuario($usuarioId);

            if (!$carrito) {
                return redirect('carrito')->with('error', 'Carrito no encontrado.');
            }

            $carritoJoineado = $this->carritoModel->obtenerProductosDelCarrito($carrito->id);

            if ($carritoJoineado->isEmpty()) {
                return redirect('carrito')->with('error', 'No hay productos en el carrito.');
            }

            $carritoFinal = $this->carritoProductoModel->obtenerCamisetasAEliminar($carritoJoineado->first());

            $this->homeModel->actualizarStock($carritoFinal);
            $this->homeModel->enviarMail($carritoFinal, $usuario->email);
            $this->carritoModel->eliminarCarritoPorIdUsuario($usuarioId);

            return redirect('carrito')->with('exitoso', 'Pago exitoso, te enviamos un mail con los detalles de tu compra.');
        } catch (Exception $e) {
            Log::error('Error al pagar el producto: ' . $e->getMessage());
            return redirect('carrito')->with('error', 'Hubo un problema al procesar el pago exitoso: ' . $e->getMessage());
        }
    }
}